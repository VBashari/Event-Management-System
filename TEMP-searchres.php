<!DOCTYPE html>
<html>
    <body>
        <?php 
            require_once './api/api/Router.php'; 
            $res = Router::route('GET', '/api/services?q=' . $_GET['q'] . '&limit=1&offset=0');
        ?>
        <div>

            <?php if (isset($res) && count($res) > 0): ?>
                <ul>
                    <?php foreach ($res as $post): ?>
                        <li data-id='<?= $post['id'];?>'><?php echo $post; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <button type="button" data-offset="0">prev</button>
        <button type="button" id="btnNext" data-offset="0">next</button>

        <script>
            const limit = 1;
            var offset = 0;

            document.getElementById("btnNext").addEventListener('click', (e) => {
                offset += limit;
                var urlParams = new URLSearchParams(window.location.search);
                var searchQuery = urlParams.get('q'); 
                
                const http = new XMLHttpRequest();
                http.open('GET', `/api/services?q=${searchQuery}&limit=${limit}&offset=${offset}`);

                http.onreadystatechange = function () {
                    if (this.readyState === 4) {
                        if (this.status === 200) {
                            const res = http.responseText; console.log(res);
                            document.write(res);
                            document.write("<h3>wowo</h3>");
                        } else {
                            alert(JSON.parse(this.response));
                        }
                    } 
                }

                http.send();
            })
        </script>
    </body>
</html>