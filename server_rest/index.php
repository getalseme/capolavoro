<?php

require 'vendor/autoload.php';

$f3 = \Base::instance();

$f3->route('GET /teachers',
    function() {
        $view = new View;
        echo $view->render('views/GET/teachers.php');
    }
);

$f3->route('GET /classes',
    function() {
        $view = new View;
        echo $view->render('views/GET/classes.php');
    }
);

$f3->route('POST /user/login',
    function() {
        $view = new View;
        echo $view->render('views/POST/login.php');
    }
);

$f3->route('DELETE /user/logout',
    function() {
        $view = new View;
        echo $view->render('views/DELETE/logout.php');
    }
);

$f3->route('GET /user/status',
    function() {
        $view = new View;
        echo $view->render('views/GET/status.php');
    }
);

$f3->route('GET /user/classes',
    function() {
        $view = new View;
        echo $view->render('views/GET/user_classes.php');
    }
);

$f3->route('GET /activity',
    function() {
        $view = new View;
        echo $view->render('views/GET/activity.php');
    }
);

$f3->route('POST /add_activity',
    function() {
        $view = new View;
        echo $view->render('views/POST/activity.php');
    }
);

$f3->route('PUT /modify/activity',
    function() {
        $view = new View;
        echo $view->render('views/PUT/activity.php');
    }
);

$f3->route('POST /delete/activity',
    function() {
        $view = new View;
        echo $view->render('views/DELETE/activity.php');
    }
);

$f3->route('GET /user/activities',
    function() {
        $view = new View;
        echo $view->render('views/GET/activity_user.php');
    }
);

$f3->route('GET /activity/all',
    function() {
        $view = new View;
        echo $view->render('views/GET/activity_all.php');
    }
);


$f3->route('GET /room/bookables',
    function() {
        $view = new View;
        echo $view->render('views/GET/room_bookable.php');
    }
);

$f3->route('GET /booking/activity',
    function() {
        $view = new View;
        echo $view->render('views/GET/booking_activity.php');
    }
);

$f3->route('GET /booking/all',
    function() {
        $view = new View;
        echo $view->render('views/GET/booking.php');
    }
);

$f3->route('POST /booking/add',
    function() {
        $view = new View;
        echo $view->render('views/POST/booking_add.php');
    }
);

$f3->route('PUT /booking',
    function() {
        $view = new View;
        echo $view->render('views/PUT/booking.php');
    }
);

$f3->route('DELETE /booking',
    function() {
        $view = new View;
        echo $view->render('views/DELETE/booking.php');
    }
);

$f3->route('DELETE /activity',
    function() {
        $view = new View;
        echo $view->render('views/DELETE/activity.php');
    }
);

$f3->route('GET /user/bookings',
    function() {
        $view = new View;
        echo $view->render('views/GET/booking_user.php');
    }
);

$f3->route('GET /booking/all',
    function() {
        $view = new View;
        echo $view->render('views/GET/booking_all.php');
    }
);

$f3->route('GET /schedule',
    function() {
        $view = new View;
        echo $view->render('views/GET/schedule.php');
    }
);

$f3->route('GET /schedule/teacher',
    function() {
        $view = new View;
        echo $view->render('views/GET/schedule_teacher.php');
    }
);

$f3->route('GET /schedule/class',
    function() {
        $view = new View;
        echo $view->render('views/GET/schedule_class.php');
    }
);


// route for the mobile app

$f3->route('GET /mobile/status',
    function() {
        $view = new View;
        echo $view->render('views/GET/app_status.php');
    }
);

$f3->route('GET /mobile/schedule',
    function() {
        $view = new View;
        echo $view->render('views/GET/app_schedule.php');
    }
);

$f3->route('GET /mobile/schedule/class',
    function() {
        $view = new View;
        echo $view->render('views/GET/app_schedule_class.php');
    }
);

$f3->route('GET /mobile/schedule/teacher',
    function() {
        $view = new View;
        echo $view->render('views/GET/app_schedule_teacher.php');
    }
);

$f3->route('GET /mobile/user/classes',
    function() {
        $view = new View;
        echo $view->render('views/GET/app_user_classes.php');
    }
);

$f3->route('GET /mobile/teachers',
    function() {
        $view = new View;
        echo $view->render('views/GET/app_teachers.php');
    }
);

$f3->route('GET /mobile/classes',
    function() {
        $view = new View;
        echo $view->render('views/GET/app_classes.php');
    }
);

$f3->run();

?>