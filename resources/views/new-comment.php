<!doctype html>
<html>
    <head>
        <title>New comment was posted</title>
    </head>
    <body>
        <h1>A new comment was posted to the Savas Labs site:</h1>
        <table>
            <tr class="header">
                <td>ID</td>
                <td><?php echo $id; ?></td>
                <td>Name</td>
                <td><?php echo $name; ?></td>
                <td>Email</td>
                <td><?php echo $email; ?></td>
                <td>Slug</td>
                <td><?php echo getenv('BASEURL') . $slug; ?></td>
                <td>IP</td>
                <td><?php echo $ip; ?></td>
            </tr>
        </table>
        <h2>Comment text</h2>
        <p><?php echo $comment; ?></td>
            <h3>Administration</h3>
            <p>To delete this comment, visit http://<?php echo getenv('BASEURL') ?>/api/comments/delete/<?php echo $id ?>/<?php echo urlencode($token); ?></p>
    </body>
</html>
