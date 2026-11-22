<?php
unset($_SESSION['login'], $_SESSION['id_user'], $_SESSION['username'], $_SESSION['nama'], $_SESSION['level']);
session_destroy();

redirect('/login');