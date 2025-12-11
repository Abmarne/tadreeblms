<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 0);

$base = realpath(__DIR__ . "/.."); // Project root
$dbConfig = __DIR__ . "/db_config.json";
$envFile = $base . "/.env";
$envExample = $base . "/.env.example";
$installedFlag = $base . "/installed";
$migrationDoneFile = $base . "/.migrations_done";
$seedDoneFile = $base . "/.seed_done";

// Installer steps for progress
$steps = ["check","composer","db_config","env","key","migrate","seed","permissions","finish"];

// -----------------------------
// Helper Functions
// -----------------------------
function send($arr){
    echo json_encode($arr);
    exit;
}

function run_cmd($cmd){
    $output = shell_exec($cmd . " 2>&1");
    if(!$output) $output = "(no output)";
    return htmlspecialchars($output);
}

function nextStep($current){
    global $steps;
    $i = array_search($current, $steps);
    return $steps[$i+1] ?? "finish";
}

// -----------------------------
// Read Step
// -----------------------------
$step = $_POST['step'] ?? 'check';

// -----------------------------
// STEP: SAVE DATABASE CONFIG
// -----------------------------
if($step === "db_save"){
    $data = [
        "host" => $_POST['db_host'] ?? "127.0.0.1",
        "name" => $_POST['db_name'] ?? "",
        "user" => $_POST['db_user'] ?? "",
        "pass" => $_POST['db_pass'] ?? ""
    ];
    file_put_contents($dbConfig, json_encode($data, JSON_PRETTY_PRINT));
    send([
        "success" => true,
        "output" => "✔ Database settings saved",
        "percent" => 30,
        "next" => "env",
        "show_db_form" => false
    ]);
}

// -----------------------------
// INSTALLER STEPS
// -----------------------------
switch($step){

    // -----------------------------
    case "check":
        $output = "";
        $allGood = true;
        $output .= "✔ PHP version: " . phpversion() . "<br>";

        $required = ["pdo_mysql","openssl","mbstring","tokenizer","xml","ctype","json","bcmath","fileinfo","curl","zip"];
        foreach($required as $ext){
            if(extension_loaded($ext)){
                $output .= "✔ $ext<br>";
            } else {
                $output .= "❌ Missing: $ext<br>";
                $allGood = false;
            }
        }

        $composer = null;
        $paths = PHP_OS_FAMILY === 'Windows'
            ? ["composer","C:\\ProgramData\\ComposerSetup\\bin\\composer.bat","C:\\composer\\composer.bat"]
            : ["/usr/local/bin/composer","/usr/bin/composer","composer"];
        foreach($paths as $p){
            $v = @shell_exec("$p --version 2>&1");
            if($v && stripos($v,"Composer")!==false){
                $composer = $p;
                break;
            }
        }
        $output .= $composer ? "✔ Composer found: $composer<br>" : "❌ Composer not found<br>";
        if(!$composer) $allGood = false;

        send([
            "success" => true,
            "output" => $output,
            "percent" => 10,
            "next" => "composer",
            "show_db_form" => false
        ]);
        break;

    // -----------------------------
    case "composer":
        $projectPath = $base;
        $isWindows = strtoupper(substr(PHP_OS,0,3))==="WIN";
        $composerCmd = null;

        $paths = $isWindows
            ? ["composer","composer.bat","composer.phar"]
            : ["composer","/usr/local/bin/composer","/usr/bin/composer"];
        foreach($paths as $p){
            $v = @shell_exec("$p --version 2>&1");
            if($v && stripos($v,"Composer")!==false){
                $composerCmd = $p;
                break;
            }
        }
        if(!$composerCmd) send([
            "success"=>false,
            "output"=>"❌ Composer not found.",
            "percent"=>15,
            "next"=>"composer",
            "show_db_form"=>false
        ]);

        $vendor = $projectPath."/vendor";
        if(!is_dir($vendor)) mkdir($vendor,0775,true);

        $cmd = $isWindows
            ? "cd /d \"$projectPath\" && $composerCmd install --no-interaction --prefer-dist 2>&1"
            : "cd \"$projectPath\" && COMPOSER_HOME=/tmp HOME=/tmp $composerCmd install --no-interaction --prefer-dist 2>&1";

        $output = run_cmd($cmd);
        send([
            "success"=>true,
            "output"=>"<pre>$output</pre>✔ Composer completed",
            "percent"=>20,
            "next"=>"db_config",
            "show_db_form"=>false
        ]);
        break;

    // -----------------------------
    case "env":
        if(!file_exists($dbConfig)) send([
            "success"=>false,
            "output"=>"❌ DB configuration missing",
            "percent"=>40,
            "next"=>"db_config",
            "show_db_form"=>true
        ]);

        $db = json_decode(file_get_contents($dbConfig),true);
        $env = file_exists($envExample) ? file_get_contents($envExample) : "";
        $env .= "\nDB_HOST={$db['host']}\nDB_DATABASE={$db['name']}\nDB_USERNAME={$db['user']}\nDB_PASSWORD=\"{$db['pass']}\"\n";
        file_put_contents($envFile,$env);

        send([
            "success"=>true,
            "output"=>"✔ .env created",
            "percent"=>50,
            "next"=>"key",
            "show_db_form"=>false
        ]);
        break;

    case "key":
        $out = run_cmd("cd $base && php artisan key:generate --force");
        send([
            "success"=>true,
            "output"=>"<pre>$out</pre>✔ APP_KEY generated",
            "percent"=>60,
            "next"=>"migrate",
            "show_db_form"=>false
        ]);
        break;

    case "migrate":
        $out = run_cmd("cd $base && php artisan migrate --force");
        file_put_contents($migrationDoneFile,"done");
        send([
            "success"=>true,
            "output"=>"<pre>$out</pre>✔ Migrations complete",
            "percent"=>75,
            "next"=>"seed",
            "show_db_form"=>false
        ]);
        break;

    case "seed":
        $out = run_cmd("cd $base && php artisan db:seed --force");
        file_put_contents($seedDoneFile,"done");
        send([
            "success"=>true,
            "output"=>"<pre>$out</pre>✔ Seeding complete",
            "percent"=>85,
            "next"=>"permissions",
            "show_db_form"=>false
        ]);
        break;

    case "permissions":
        @chmod($base."/storage",0777);
        @chmod($base."/bootstrap/cache",0777);
        send([
            "success"=>true,
            "output"=>"✔ Permissions set",
            "percent"=>95,
            "next"=>"finish",
            "show_db_form"=>false
        ]);
        break;

    case "finish":
        file_put_contents($installedFlag,"installed");
        send([
            "success"=>true,
            "output"=>"🎉 Installation complete!",
            "percent"=>100,
            "next"=>"finish",
            "show_db_form"=>false
        ]);
        break;

    default:
        send([
            "success"=>false,
            "output"=>"Unknown step: $step",
            "percent"=>0,
            "next"=>"check",
            "show_db_form"=>false
        ]);
        break;
}
