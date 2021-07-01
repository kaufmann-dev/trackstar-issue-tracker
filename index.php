<?php
    # init db
    $db = new PDO("mysql:host=162.55.215.235;port=3306;dbname=trackstar", "hombo", "8573");
    $db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

    # get projects
    $projects_stmt = $db->prepare("SELECT * FROM projects");
    $projects_stmt->execute();
    $projects = $projects_stmt->fetchAll();

    # delete
    if(isset($_POST["delete"])){
        $delete = $_POST["delete"];
        $delete_stmt = $db->prepare("DELETE FROM projects WHERE project_id=:projectID");
        $delete_stmt->bindParam(':projectID', $delete, PDO::PARAM_INT);
        $delete_stmt->execute();
        header("Refresh:0");
    }

    # change project data
    if(isset($_POST["change"])){
        $change = $_POST["change"];
        $change_stmt = $db->prepare("UPDATE projects SET title =:pTitle, description =:pDesc WHERE project_id =:pId");
        $change_stmt->bindParam(':pTitle', $_POST["change_title"], PDO::PARAM_INT);
        $change_stmt->bindParam(':pDesc', $_POST["change_desc"], PDO::PARAM_INT);
      #$change_stmt->bindParam(':pCreated', date('l dS \o\f F Y h:i:s A', strtotime($_POST["change_created"])), PDO::PARAM_INT);
        $change_stmt->bindParam(':pId', $change, PDO::PARAM_INT);
        $change_stmt->execute();
        header("Refresh:0");
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Trackstar</title>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="px-2 py-1 rounded border float-end mt-2"><i>Benutzer:</i> User 1</div>
        <h1>Projektübersicht</h1>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Beschreibung</th>
                <th>Erstellt</th>
                <th>Aktionen</th>
            </tr>
            </thead>
            <tbody> <?php
                foreach ($projects as $project){
                    echo"<tr><td>".$project["title"]."</td>";
                    echo"<td>".$project["description"]."</td>";
                    echo"<td>".$project["created_at"]."</td>"; ?>
                    <td>
                        <form class="d-inline" action="" method="post"><input type="hidden" name="delete" value="<?php echo $project["project_id"]; ?>"><input class="btn btn-sm btn-danger" type="submit" value="Löschen"></form>
                        <form class="d-inline" action="" method="post"><input type="hidden" name="edit" value="<?php echo $project["project_id"]; ?>"><input class="btn btn-sm btn-dark" type="submit" value="Bearbeiten"></form>
                        <form class="d-inline" action="" method="post"><input type="hidden" name="issues" value="<?php echo $project["project_id"]; ?>"><input class="btn btn-sm btn-warning" type="submit" value="Probleme"></form>
                    </td></tr>
                <?php }
            ?> </tbody>
        </table>
    </div> <?php
    # edit
    if(isset($_POST["edit"])){
        $edit = $_POST["edit"];
        $crazy_index = array_search($edit, array_column($projects, 'project_id'));
        ?>
        <div class="text-center">
        <form class="form border d-inline-block p-3 rounded text-start" action="" method="post">
            <label for="title">Name</label><br>
            <input name="change_title" type="text" value="<?php echo $projects[$crazy_index]["title"]; ?>"><br><br>
            <label for="title">Beschreibung</label><br>
            <input name="change_desc" type="text" value="<?php echo $projects[$crazy_index]["description"]; ?>"><br><br>
            
            <input type="hidden" name="change" value="true">
            <input type="submit" class="btn btn-primary btn-sm" value="Aktualisieren">
        </form>
        </div>
    <?php }

    # issues
    if(isset($_POST["issues"])){
        $issues_project_id = $_POST["issues"];
        $issues_stmt = $db->prepare("SELECT * FROM issues where project_id =:p_id");
        $issues_stmt->bindParam(':p_id', $issues_project_id, PDO::PARAM_INT);
        $issues_stmt->execute();
        $issues = $issues_stmt->fetchAll();
        ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Kategorie</th>
                <th>Beschreibung</th>
                <th>Erstellt</th>
                <th>Aktionen</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($issues as $issue){
                ?>
                <tr>
                    <td><?php echo $issue["category"]; ?></td>
                    <td><?php echo $issue["description"]; ?></td>
                    <td><?php echo $issue["created_at"]; ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    <?php
    }
    ?> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
