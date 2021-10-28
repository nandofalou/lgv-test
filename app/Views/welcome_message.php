<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Teste Loja virtual</title>
        <meta name="description" content="codeigniter 4">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/png" href="/favicon.ico"/>
    </head>
    <body>

        <header>

            <div class="heroe">

                <h1>Welcome to CodeIgniter <?= CodeIgniter\CodeIgniter::CI_VERSION ?></h1>

                

            </div>

        </header>

        <!-- CONTENT -->

        <section>

            <h1>Banco de dados atualizados</h1>

        </section>


        <!-- FOOTER: DEBUG INFO + COPYRIGHTS -->

        <footer>
            <div class="environment">

                <p>Page rendered in {elapsed_time} seconds</p>

                <p>Environment: <?= ENVIRONMENT ?></p>

            </div>

            <div class="copyrights">

                <p>&copy; <?= date('Y') ?> CodeIgniter Foundation. CodeIgniter is open source project released under the MIT
                    open source licence.</p>

            </div>

        </footer>
    </body>
</html>
