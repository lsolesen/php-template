PHP Template Engine
==

Very simple template engine for PHP Version 4. It is based on the article [Beyond the template engine](http://articles.sitepoint.com/article/beyond-template-engine) by Brian Lozier.

For a more current PHP 5 implementation of the same ideas, check out [phpsavant.com](http://phpsavant.com/).

Example
--

    <?php
    $path = './templates/';

    tpl = new Template($path);
    $tpl->set('title', 'User List');
    $body = new Template($path);
    $body->set('user_list', fetch_user_list());
    $tpl->set('body', $body->fetch('user_list.tpl.php'));
    echo $tpl->fetch('index.tpl.php');
    ?>

And it can be used with the following templates.

    <!-- user_list.tpl.php -->
    <table>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Banned</th>
        </tr>
        <? foreach($user_list as $user): ?>
        <tr>
            <td align="center"><?=$user['id'];?></td>
            <td><?=$user['name'];?></td>
            <td><a href="mailto:<?=$user['email'];?>"><?=$user['email'];?></a></td>
            <td align="center"><?=($user['banned'] ? 'X' : '&nbsp;');?></td>
        </tr>
        <? endforeach; ?>
    </table>

    <!-- index.tpl.php -->

    <html>
        <head>
            <title><?=$title;?></title>
        </head>
        <body>
            <h2><?=$title;?></h2>
            <?=$body;?>
        </body>
    </html>
