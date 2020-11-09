<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();

    include "config/config.php";
    include "controller.php";
?>
<html lang="it">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <title>Test Emoby</title>
  </head>
  <style>
    .loader_wrap {
        position: absolute;
        top: 0;
        left: 0px;
        width: 100vw;
        height: 100vh;
        background: #0000008f;
    }

    .spinner_loader {
        position: absolute;
        top: 50%;
        left: 50%;
        color: white !important;
    }
  </style>
  <body>

    <div class="container-fluid">
      <div class="row">
        <div class="col-12 text-center mb-4">
          <h1>Test API Emoby</h1>
          <div class="button-container">
            <button class="btn btn-primary" id="connect_api">Connetti e scarica</button>
            <button class="btn btn-secondary" id="show_tables">Mostra risultati</button>
          </div>
        </div>
        <div class="col-12">
          <table class="table table-bordered table-striped table-responsive">
            <thead>
              <tr>
                <?php
                foreach (getTableColumns() as $column) {
                  ?>
                  <th scope="col"><?=$column['Field']?></th>
                  <?php
                }
                ?>
              </tr>
            </thead>
            <tbody id="ajax_content_dest">
                <?php
                $user_list = getUsers();
                if (count($user_list) > 0) {
                  foreach ($user_list as $value) {
                    ?>
                    <tr>
                    <?php
                    foreach (getTableColumns() as $field) {
                      ?>
                      <td><?=$value[$field['Field']]?></td>
                      <?php
                    }
                    ?>
                    </tr>
                    <?php
                  }
                } else {
                  ?>
                  <tr>
                    <td colspan="<?=count(getTableColumns())?>" class="text-center">Nessun utente trovato</td>
                  <tr>
                  <?php
                }
                ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            
          </div>
        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script type="text/javascript">

      function alert(title, html) {
        $('#alertModal .modal-title').text(title);
        $('#alertModal .modal-body').html(html);
        $('#alertModal').modal('show');
      }

      function getUsersData(token) {
        $.ajax({
          url: "ajax.php",
          method: "post",
          data: {
            action: "getUsersData",
            token: token
          },
          beforeSend: function() {
            var html = '<div class="loader_wrap"><div class="spinner-border text-primary spinner_loader" role="status"><span class="sr-only">Loading...</span></div></div>';
            $('body').append(html);
          }
        }).done(function(res) {
            $('body .loader_wrap').remove();
            var html = '<p class="text-center mt-3">Salvataggio completato</p>';
            alert("Emoby API",html);
          });
      }

      $(document).ready(function() {
        
        $(document).on('click','#connect_api',function() {
          $.ajax({
            url: "https://prod-api.emoby.it/auth",
            method: "post",
            dataType: "json",
            data: {
              utente: "umberto@yopmail.com",
              password: "#XqwAH79uy",
              azienda: "124"
            },
            beforeSend: function() {
              var html = '<div class="loader_wrap"><div class="spinner-border text-primary spinner_loader" role="status"><span class="sr-only">Loading...</span></div></div>';
              $('body').append(html);
            }
          }).done(function(res) {
            $('body .loader_wrap').remove();
            if (res.result=="success") {
              getUsersData(res.token);
            } else {
              var html = '<p class="text-center mt-3">Errore di autenticazione</p>';
              alert("Attenzione",html);
            }
          });
        });

        $(document).on('click','#show_tables',function() {
          $.ajax({
            url: "ajax.php",
            method: "post",
            dataType: "html",
            data: {
              action: "reloadTable"
            }
          }).done(function(html) {
            $('#ajax_content_dest').html(html);
          });
        });

      });
    </script>
  </body>
</html>