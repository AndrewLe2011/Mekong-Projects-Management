<?php
require "config.php";
ob_start();
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/table.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" />
    <!-- styling -->

    <!-- bootstrap 4.6.0 -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <!-- bootstrap 4.6.0 -->

    <!-- handlers for table and form -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $(window).on('load', function() {
                setTimeout(() => {
                    $('#on-loading').removeClass("d-block");
                    $('#on-loading').addClass("d-none");
                    $('#done-loading').removeClass("d-none");
                    $('#done-loading').addClass("d-block");
                    $('table').removeClass("invisible");
                    $('table').addClass("display");
                }, 2000);

                $("#form-input-area").submit(function(event) {
                    var vForm = $(this);
                    if (vForm[0].checkValidity() === false) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    vForm.addClass('was-validated');
                });
            })

            $('#data-table').DataTable({
                "scrollX": true,
                "oLanguage": {
                    "sLengthMenu": 'Display <select>' +
                        '<option value="10">10</option>' +
                        '<option value="25">25</option>' +
                        '<option value="50">50</option>' +
                        '<option value="100">100</option>' +
                        '<option value="200">200</option>' +
                        '<option value="500">500</option>' +
                        '<option value="1000">1000</option>' +
                        '<option value="2000">All</option>' +
                        '</select> records'
                }
            });
        });
    </script>
    <!-- handlers for table and form -->

    <title>Projects Management App</title>
</head>

<body>
    <!-- different request handlers -->
    <?php
    // handle CREATE request
    if (isset($_GET['save']) && $_GET['save'] == 'save') {
        $file = file_get_contents('gs://asm1cc21b/project.csv', true);
        $breakDownToRow = explode("\n", $file);
        $pushThis = '';
        foreach ($_GET as $key => $value) {
            if ($key == "id") break;
            if ($key != "lastupdated") $pushThis .= '"' . $value . '",';
            else $pushThis .= '"' . $value . '"';
        }


        array_splice($breakDownToRow, 1, 0, $pushThis);
        $file = join("\n", $breakDownToRow);
        file_put_contents('gs://asm1cc21b/project.csv', $file);
    }
    // handle DELETE request
    if (isset($_GET['action']) && $_GET['action'] == 'delete') {
        $contents = file_get_contents('gs://asm1cc21b/project.csv', true);
        $breakDownToRow = explode("\n", $contents);

        $fullUrl = parse_url($_SERVER['REQUEST_URI']);
        preg_match_all('/\&(.*?)\!/', urldecode($fullUrl['query']), $matches);
        $beforeDelete = join("", $matches[0]);
        preg_match_all('/\=(.*?)\!/', $beforeDelete, $matches2);

        $deleteThis = '';
        $count = 0;
        foreach ($matches2[1] as $key => $value) {
            if ($count != (count($matches2[1]) - 2)) {
                $deleteThis .= '"' . $value . '",';
            } else {
                $deleteThis .= '"' . $value . '"';
                break;
            }
            $count++;
        }

        for ($i = 0; $i < count($breakDownToRow); $i++) {
            if ($breakDownToRow[$i] == $deleteThis) {
                unset($breakDownToRow[$i]);
                break;
            }
        }
        
        $contents = join("\n", $breakDownToRow);
        file_put_contents('gs://asm1cc21b/project.csv', $contents);
    }
    // handle UPDATE request
    if (isset($_GET['save']) && $_GET['save'] == 'update') {
        $contents = file_get_contents('gs://asm1cc21b/project.csv', true);
        $breakDownToRow = explode("\n", $contents);

        $fullUpdateUrl = parse_url($_SERVER['REQUEST_URI']);
        preg_match_all('/\=(.*?)\&/', $fullUpdateUrl['query'], $matches);
        $line = urldecode($matches[1][23]);

        $updateTo = '';
        foreach ($matches[1] as $key => $value) {
            if ($key < 22) {
                $updateTo .= '"' . urldecode($value) . '",';
            } else {
                $updateTo .= '"' . urldecode($value) . '"';
                break;
            }
        }
        $breakDownToRow[$line] = $updateTo;
        $contents = join("\n", $breakDownToRow);
        file_put_contents('gs://asm1cc21b/project.csv', $contents);
    }
    ?>
    <!-- different request handlers -->

    <!-- navigation bar start -->
    <div id="navbar-container">
        <nav class="navbar navbar-expand-md navbar-light" style="justify-content: space-around; font-family:Georgia, 'Times New Roman', Times, serif">
            <a href="https://asm1cc21b.et.r.appspot.com/" class="navbar-brand ml-3">
                <p class="h3 text-danger">Projects Management App</p>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav">
                    <a href="https://asm1cc21b.et.r.appspot.com/" class="nav-item nav-link active"><strong>Home</strong></a>
                    <a href="https://asm1cc21b.et.r.appspot.com/query.php" class="nav-item nav-link"><strong>Querying</strong></a>
                </div>
            </div>
        </nav>
    </div>
    <!-- navigation bar end -->

    <!-- notifications for on and done loading  -->
    <?php
    if (isset($_GET['save']) && $_GET['save'] == 'save') {
        echo '<div id="on-loading" class="alert alert-info d-block text-center py-3 mb-5">Creating new data';
        echo '<span class="spinner-border spinner-border-sm text-info ml-2"></span>';
        echo '</div>';
    } else if (isset($_GET['action']) && $_GET['action'] == 'delete') {
        echo '<div id="on-loading" class="alert alert-danger d-block text-center py-3 mb-5">Deleting chosen data';
        echo '<span class="spinner-border spinner-border-sm text-danger ml-2"></span>';
        echo '</div>';
    } else if (isset($_GET['action']) && $_GET['action'] == 'update') {
        echo '<div id="on-loading" class="alert alert-warning d-block text-center py-3 mb-5">Inputting chosen data to update';
        echo '<span class="spinner-border spinner-border-sm text-warning ml-2"></span>';
        echo '</div>';
    } else if (isset($_GET['save']) && $_GET['save'] == 'update') {
        echo '<div id="on-loading" class="alert alert-warning d-block text-center py-3 mb-5">Updating data from new inputs';
        echo '<span class="spinner-border spinner-border-sm text-warning ml-2"></span>';
        echo '</div>';
    } else {
        echo '<div id="on-loading" class="alert alert-primary d-block text-center py-3 mb-5">Loading';
        echo '<span class="spinner-border spinner-border-sm text-primary ml-2"></span>';
        echo '</div>';
    }
    ?>

    <?php
    if ((isset($_GET['save']) && $_GET['save'] == 'save') || (isset($_GET['action']) && $_GET['action'] == 'delete') || (isset($_GET['save']) && $_GET['save'] == 'update')) {
        echo '<div id="done-loading" class="d-none alert alert-success alert-dimissible text-center py-3 mb-5 fade show">';
        echo 'Successful! Refreshing to fetch changes';
        echo '<span class="spinner-border spinner-border-sm text-success ml-2"></span>';
        echo '</div>';
        echo '<meta http-equiv="refresh" content="10;URL=\'https://asm1cc21b.et.r.appspot.com/\'">';
    } else {
        echo '<div id="done-loading" class="d-none alert alert-success alert-dimissible text-center py-3 mb-5 fade show">';
        echo 'All set! 😊';
        echo '
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>';
        echo '</div>';
    }
    ?>
    <!-- notifications for on and done loading  -->

    <!-- get data to display -->
    <?php
    $contents = file_get_contents('gs://asm1cc21b/project.csv', true);
    $breakDownToRow = explode("\n", $contents);
    array_pop($breakDownToRow);
    ?>
    <!-- get data to display -->

    <!-- table start -->
    <div class="container bg-light my-5 px-4 py-4" id="container1">
        <h2 id="form-purpose" class="text-center mb-3" style="color: #141727; font-family:Georgia, 'Times New Roman', Times, serif">Projects Information</h2>
        <table id="data-table" class="nowrap invisible" style="width:100%;">
            <thead>
                <tr>
                    <?php
                    echo '<th>Action</th>';
                    $breakDownToElement = explode(",", $breakDownToRow[0]);
                    for ($i = 0; $i < count($breakDownToElement); $i++) {
                        $header = substr($breakDownToElement[$i], 1, -1);
                        echo '<th>' . $header . '</th>';
                    }
                    ?>
                </tr>
            </thead>

            <tbody>
                <?php
                for ($i = 1; $i < count($breakDownToRow); $i++) {
                    echo "<tr>";
                    $attributes = [
                        "projectname", "subtype", "currentstatus", "capacity", "completeyear",
                        "devcountry", "devcompany", "lencountry", "lencompany", "epccountry", "epccompany",
                        "countryonly", "state", "district", "tributary", "latitude", "longitude",
                        "proxi", "avgout", "datasource", "moreinfo", "link", "lastupdated"
                    ];

                    $urlDelete = 'https://asm1cc21b.et.r.appspot.com/home.php?action=delete';
                    $urlUpdate = 'https://asm1cc21b.et.r.appspot.com/home.php?action=update';
                    preg_match_all('/"(.*?)"/', $breakDownToRow[$i], $matches);
                    for ($j = 0; $j < count($matches[1]); $j++) {
                        $urlDelete .= '&';
                        $urlDelete .= $attributes[$j] . '=' . $matches[1][$j] . "!";
                        $urlUpdate .= '&';
                        $urlUpdate .= $attributes[$j] . '=' . $matches[1][$j] . "!";
                    }
                    $urlDelete .= '&id=' . $i . '!';
                    $urlUpdate .= '&id=' . $i . '!';

                    echo '
                        <td>
                            <a href="' . $urlUpdate . '" class="btn btn-warning d-inline-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                </svg>
                            </a>
                            <a href="' . $urlDelete . '" class="btn btn-danger d-inline-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                                </svg>
                            </a>
                        </td>
                    ';

                    for ($j = 0; $j < count($matches[1]); $j++) {
                        if ($matches[1][$j] == "" || $matches[1][$j] == " ")
                            echo '<td class="text-muted"><i>null</i></td>';
                        else
                            echo '<td>' . $matches[1][$j] . '</td>';
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- table end -->

    <!-- adding and creating form start -->
    <?php if (isset($_GET['action']) && $_GET['action'] == 'update') : ?>
        <div class="container bg-light my-5 px-4 py-4 d-flex flex-column justify-content-center align-items-center" id="container2">
        <?php else : ?>
            <div class="container bg-light mb-5 px-4 py-4 d-flex flex-column justify-content-center align-items-center" id="container2">
            <?php endif; ?>
            <?php if (isset($_GET['action']) && $_GET['action'] == 'update') : ?>
                <h2 id="form-purpose" class="text-center mb-3" style="color: #141727; font-family:Georgia, 'Times New Roman', Times, serif">Update Data</h2>
            <?php else : ?>
                <h2 id="form-purpose" class="text-center mb-3" style="color: #141727; font-family:Georgia, 'Times New Roman', Times, serif">Create New Data</h2>
            <?php endif; ?>

            <?php
            $fullUpdateUrl = parse_url($_SERVER['REQUEST_URI']);
            preg_match_all('/\&(.*?)\!/', urldecode($fullUpdateUrl['query']), $matches);
            ?>
            <form id="form-input-area" action="home.php" method="GET" class="row w-100 justify-content-center" autocomplete="off" novalidate>
                <div class="col-md-12 mb-3">
                    <label id="projectnamelabel" for="projectname">Project Name:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", ($matches[1][0]));
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> required type="text" id="projectname" name="projectname" placeholder="Enter project name" pattern="^(\(([^)]+)\))?[[:punct:]]?\p{Lu}+(?:[\s'-]?[\p{L}\d]+)+(\(([^)]+)\))*$">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">[REQUIRED] Please fill in the required field.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="subtypelabel" for="subtype">Subtype:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", ($matches[1][1]));
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> required type="text" id="subtype" name="subtype" placeholder="Example: Solar, Gas, ..." pattern="^[a-zA-Z]+$">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">[REQUIRED] Allow letters only.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="currentstatuslabel" for="currentstatus">Current Status:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", ($matches[1][2]));
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> required type="text" id="currentstatus" name="currentstatus" placeholder="Put 'Unknown' if not know (case-sensitive)" pattern="^(Operational|Planned|Under Construction|Cancelled|Unknown)$">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">[REQUIRED] Valid status: Operational, Planned, Under Construction, Cancelled, Unknown.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="capacitylabel" for="capacity">Capacity (MV):</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", ($matches[1][3]));
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> type="text" id="capacity" name="capacity" placeholder="Leave blank if not know" pattern="^(-?\d+\.\d+)$|^(-?\d+)$">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">Invalid capacity.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="completeyearlabel" for="completeyear">Year of Completion:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", ($matches[1][4]));
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> type="text" id="completeyear" name="completeyear" placeholder="Leave blank if not know" pattern="^[1-9]{1}[0-9]{3}$">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">Valid year: 1xxx or 2xxx.</div>
                </div>

                <!-- countries and companies -->
                <div class="col-md-6 mb-3">
                    <label id="devcountrylabel" for="devcountry">Sponsor/Developer Country:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", ($matches[1][5]));
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> type="text" id="devcountry" name="devcountry" placeholder='Separate a list of countries by ";" only, if needed' pattern="^([a-zA-Z ]{2,}(;|; )?)+$">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">Valid country: two or more characters, letters and spaces only.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="devcompanylabel" for="devcompany">Sponsor/Developer Company:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", ($matches[1][6]));
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> type="text" id="devcompany" name="devcompany" placeholder="Leave blank if not know" pattern="^(\(([^)]+)\))?[[:punct:]]?\p{Lu}+(?:[\s'-]?[\p{L}\d]+)+(\(([^)]+)\))*$">
                    <div class="valid-feedback">Good to go!</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="lencountrylabel" for="lencountry">Lender/Financier Country:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", ($matches[1][7]));
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> type="text" id="lencountry" name="lencountry" placeholder='Separate a list of countries by ";" only, if needed' pattern="^([a-zA-Z ]{2,}(;|; )?)+$">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">Valid country: two or more characters, letters and spaces only.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="lencompanylabel" for="lencompany">Lender/Financier Company:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", ($matches[1][8]));
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> type="text" id="lencompany" name="lencompany" placeholder="Leave blank if not know" pattern="^(\(([^)]+)\))?[[:punct:]]?\p{Lu}+(?:[\s'-]?[\p{L}\d]+)+(\(([^)]+)\))*$">
                    <div class="valid-feedback">Good to go!</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="epccountrylabel" for="epccountry">Construction/EPC Country List:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", ($matches[1][9]));
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> type="text" id="epccountry" name="epccountry" placeholder='Separate a list of countries by ";" only, if needed' pattern="^([a-zA-Z ]{2,}(;|; )?)+$">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">Valid country: two or more characters, letters and spaces only.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="epccompanylabel" for="epccompany">Construction Company/EPC Participant:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", $matches[1][10]);
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> type="text" id="epccompany" name="epccompany" placeholder="Leave blank if not know" pattern="^(\(([^)]+)\))?[[:punct:]]?\p{Lu}+(?:[\s'-]?[\p{L}\d]+)+(\(([^)]+)\))*$">
                    <div class="valid-feedback">Good to go!</div>
                </div>
                <!-- countries and companies -->

                <div class="col-md-6 mb-3">
                    <label id="countryonlylabel" for="countryonly">Country:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", $matches[1][11]);
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> required type="text" id="countryonly" name="countryonly" placeholder='Separate a list of countries by ";" only, if needed' pattern="^([a-zA-Z ]{2,}(;|; )?)+$">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">[REQUIRED] Valid country: two or more characters, letters and spaces only.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="statelabel" for="state">Province/State</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", $matches[1][12]);
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> required type="text" id="state" name="state" placeholder="Enter state/province name" pattern="^[^0-9]+$">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">[REQUIRED] Digits or numeric characters are not allowed.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="districtlabel" for="district">District:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", $matches[1][13]);
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> required type="text" id="district" name="district" placeholder="Enter district name" pattern="^[^0-9]+$">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">[REQUIRED] Digits or numeric characters are not allowed.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="tributarylabel" for="tributary">Tributary:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", $matches[1][14]);
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> type="text" id="tributary" name="tributary" placeholder="Leave blank if not know" pattern="^[^0-9]+$">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">[REQUIRED] Digits or numeric characters are not allowed.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="latitudelabel" for="latitude">Latitude:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", $matches[1][15]);
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> required type="text" id="latitude" name="latitude" placeholder="Enter latitude" pattern="^(-?\d+\.\d+)$|^(-?\d+)$">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">[REQUIRED] Invalid latitude. Check if only numeric.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="longtitudelabel" for="longtitude">Longtitude</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", $matches[1][16]);
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> required type="text" id="longtitude" name="longtitude" placeholder="Enter longtitude" pattern="^(-?\d+\.\d+)$|^(-?\d+)$">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">[REQUIRED] Invalid longtitude. Check if only numeric.</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label id="proxilabel" for="proxi">Proximity:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", $matches[1][17]);
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> type="text" id="proxi" name="proxi" placeholder="Leave blank if not know" pattern="^[^0-9]+$">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">[REQUIRED] Digits or numeric characters are not allowed.</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label id="avgoutlabel" for="avgout">Avg. Annual Output (MWh):</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", $matches[1][18]);
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> type="text" id="avgout" name="avgout" placeholder="Leave blank if not know" pattern="^(-?\d+\.\d+)$|^(-?\d+)$">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">Invalid AAO. Check if numeric only.</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label id="datasourcelabel" for="datasource">Data Source:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", $matches[1][19]);
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> type="text" id="datasource" name="datasource" placeholder="Links or short description. Otherwise, leave blank">
                    <div class="valid-feedback">Good to go!</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="moreinfolabel" for="moreinfo">Announcement/More Information:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", $matches[1][20]);
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> type="text" id="moreinfo" name="moreinfo" placeholder="Links or short description. Otherwise, leave blank">
                    <div class="valid-feedback">Good to go!</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="linklabel" for="link">Link:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", $matches[1][21]);
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                }
                                                ?> type="text" id="link" name="link" placeholder="Leave blank if not know" pattern="https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)">
                    <div class="valid-feedback">Good to go!</div>
                    <div class="invalid-feedback">Invalid URI.</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label id="lastupdatedlabel" for="lastupdated">Last Updated:</label>
                    <input class="form-control" <?php
                                                if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                    $breakToGetValue = explode("=", $matches[1][22]);
                                                    echo 'value="' . $breakToGetValue[1] . '"';
                                                } else {
                                                    echo 'value="' . date("m/d/Y") . '"';
                                                }
                                                ?> type="text" id="lastupdated" name="lastupdated" placeholder="Leave black if not know">
                </div>

                <input type="hidden" id="id" name="id" <?php
                                                        if (isset($_GET['action']) && $_GET['action'] == 'update') {
                                                            $breakToGetValue = explode("=", ($matches[1][23]));
                                                            echo 'value="' . $breakToGetValue[1] . '"';
                                                        }
                                                        ?>>

                <?php if (isset($_GET['action']) && $_GET['action'] == 'update') : ?>
                    <div class="d-flex flex-column justify-content-center align-items-center w-100 mt-4">
                        <button class="btn btn-warning btn-lg btn-block mb-2 w-50" type="submit" name="save" value="update" style="border-radius: 30px;">Update</button>
                        <a href="https://asm1cc21b.et.r.appspot.com/" class="btn btn-secondary btn-md btn-block w-50" style="border-radius: 20px;">Create</a>
                    </div>
                <?php else : ?>
                    <div class="d-flex justify-content-center w-100 mt-4">
                        <button class="btn btn-primary btn-lg btn-block w-50" type="submit" name="save" value="save" style="border-radius: 30px;">Save</button>
                    </div>
                <?php endif; ?>
            </form>
            </div>
            <!-- adding and creating form end -->
</body>

</html>