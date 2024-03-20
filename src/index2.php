</head>

<body>
        <h1>PDO protegido contra inyecciones SQL</h1>

        <?php
                if( isset($_POST["user"])) {

                        $dbhost = $_ENV["DB_HOST"];
                        $dbname = $_ENV["DB_NAME"];
                        $dbuser = $_ENV["DB_USER"];
                        $dbpass = $_ENV["DB_PASSWORD"];

                        # Conexión a MySQL
                        $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);

                        $username = $_POST["user"];
                        $pass = $_POST["password"];

                        # Preparamos la consulta con parámetros
                        $consulta = $pdo->prepare("SELECT * FROM users WHERE name=? AND password=SHA2(?,512)");

                        # Vinculamos los parámetros
                        $consulta->bindParam(1, $username);
                        $consulta->bindParam(2, $pass);

                        # Ejecutamos la consulta
                        $consulta->execute();

                        # Gestión de errores
                        if($consulta->errorInfo()[1]) {
                                echo "<p>ERROR: ".$consulta->errorInfo()[2]."</p>\n";
                                die;
                        }

                        if($consulta->rowCount() >= 1)
                                # Hay 1 resultado o más de usuarios con ese nombre y contraseña
                                foreach($consulta as $user) {
                                        echo "<div class='user'>Hola ".$user["name"]." (".$user["role"].").</div>";
                                }
                        else
                                echo "<div class='user'>No hay ningún usuario con ese nombre o contraseña.</div>";
                }
        ?>

        <fieldset>
        <legend>Formulario de inicio de sesión</legend>
        <form method="post">
                Usuario: <input type="text" name="user" /><br>
                Contraseña: <input type="text" name="password" /><br>
                <input type="submit" /><br>
        </form>
        </fieldset>

</body>

</html>