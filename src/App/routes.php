<?php
/*
 * Class Router
 * Маршрутизация с използване на регулярни изрази по идея на "nikic"
 * https://nikic.github.io/2014/02/18/Fast-request-routing-using-regular-expressions.html
 *
 * използване:
 * -------------------------------
 * Router::get('search/{id}', ['Admin/Folder/class@action', 'name'=>'search']);
 * Router::post('search/{id}', ['Admin/Folder/class@action', 'name'=>'search']);
 * Router::head('search/{id}', ['Admin/Folder/class@action', 'name'=>'search']);
 * Router::put('search/{id}', ['Admin/Folder/class@action', 'name'=>'search']);
 * Router::delete('search/{id}', ['Admin/Folder/class@action', 'name'=>'search']);
 * Router::any('search/{id}', ['Admin/Folder/class@action', 'name'=>'search']);
 * Router::methods(['post', 'get'],'search/{id}', ['Admin/Folder/class@action', 'name'=>'search']);
 * Router::get('route-callback/{doc:format=pdf}/{id}', [function($doc, $id){
 *   echo 'Document name: '. $doc . ' id: ' . $id;
 *  }, 'name'=>'callback']);
 *
 * Optional Parameters:
 * -------------------------------
 * Router::any('search/{id?}', ['Admin/Folder/class@action', 'name'=>'search']);
 *
 * Regular Expression Constraints:
 * -------------------------------
 * Router::get('route/{slug}/{id:[0-9]{0,5}}', ['Admin3/controller@action', 'name'=>'route']);
 * Router::get('route/{lang:(en|bg)}/{slug}/edit/{post}/{id?:\d+}', ['test/controller@route_post']);
 * Router::get('route/{slug?:^\w+((?:\.pdf))$}', ['Admin3/controller@action', 'name'=>'route']);
 *
 * File ext: match (route/document & route/document.html)
 * ------------------------------
 * Router::get('route/{slug?:format=html}', ['Admin3/controller@action', 'name'=>'route']);
 *
 * $router = new Router();
 * $route = $router->dispatch('post/55'); // return array
 */

use Core\Bootstrap\Router;

// Create Routes colection
try {

// Uncomment for Shut down your Site
    /*Router::site_down(function(){
        echo 'Сайта е в ремонт !';
        exit;
    });*/

// Install
    Router::get('install/{mode?}',
        ['Install@install']
    );
    Router::post('installAdministrator',
        ['Install@installAdministrator']
    );
// Search & result
    Router::get('/',
        ['Rooms@search', 'name' => 'index']
    );
    Router::get('lang/{lang}',
        ['Rooms@set_lang']
    );
    Router::any('error-page/{id}',
        [
            'ErrorPage@show',
            'name' => 'error'
        ]
    );
    Router::methods(['get', 'post'], 'search',
        ['Rooms@search', 'name' => 'search']
    );
    Router::get('room/{id}',
        ['Rooms@roominfo', 'name' => 'roominfo']
    );
    Router::methods(['post', 'get'], 'add_to_bag',
        ['Rooms@add_to_bag', 'name' => 'add_to_bag']
    );
    Router::methods(['post', 'get'], 'count_added/{id?:[0-9]}',
        ['Rooms@count_added', 'name' => 'count_added']
    );
    Router::methods(['post', 'get'], 'display_booked_rooms',
        ['Rooms@display_added', 'name' => 'display_added']
    );
    Router::methods(['post', 'get'], 'delete_added/{id?}',
        ['Rooms@delete_added', 'name' => 'delete_added']
    );

// Booking Routes
    Router::get('ajaxCheck/{id?}',
        ['RoomBooking@ajaxCheck', 'name' => 'ajaxCheck']
    );
    Router::get('confirm',
        ['RoomBooking@confirm', 'name' => 'confirm']
    );
    Router::methods(['get', 'post'], 'booking',
        ['RoomBooking@booking', 'name' => 'booking']
    );
    Router::get('order',
        ['RoomBooking@order', 'name' => 'order']
    );
    Router::post('order',
        ['RoomBooking@order', 'name' => 'order']
    );
// Dashboard Routes -------------------------------------------------------------------------------------------
//Login
    Router::get('booking-dashboard/login',
        ['Dashboard/DashboardLogin@login', 'name' => 'login']
    );
    Router::methods(['post', 'get'], 'booking-dashboard/verify',
        ['Dashboard/DashboardLogin@verify']
    );
    Router::methods(['post', 'get'], 'booking-dashboard/logout',
        ['Dashboard/DashboardLogin@logout', 'name' => 'logout']
    );
//Users
    Router::methods(['post', 'get'], 'booking-dashboard/users',
        ['Dashboard/DashboardUsers@users', 'name' => 'users']
    );
    Router::methods(['post', 'get'], 'booking-dashboard/user-new',
        ['Dashboard/DashboardUsers@newUser', 'name' => 'user_new']
    );
    Router::methods(['post', 'get'], 'booking-dashboard/user-profile/{user?}',
        ['Dashboard/DashboardUsers@userProfile', 'name' => 'user_profile']
    );
    Router::methods(['post', 'get'], 'booking-dashboard/user-profile-update',
        [
            'Dashboard/DashboardUsers@updateUserProfile',
            'name' => 'user_profile_update'
        ]
    );
    Router::methods(['post', 'get'], 'booking-dashboard/user-profile-delete',
        [
            'Dashboard/DashboardUsers@deleteUser',
            'name' => 'user_profile_delete'
        ]
    );

// Dashboard
    Router::get('booking-dashboard',
        ['Dashboard/Dashboard@index', 'name' => 'dashboard']
    );

//  Room Types
    Router::get('booking-dashboard/room-types',
        ['Dashboard/RoomTypes@roomTypes', 'name' => 'room_types']
    );
    Router::get('booking-dashboard/room-types/new',
        ['Dashboard/RoomTypes@newRoomType', 'name' => 'room_types_new']
    );
    Router::post('booking-dashboard/room-types/new',
        ['Dashboard/RoomTypes@add', 'name' => 'room_types_new']
    );
    Router::get('booking-dashboard/room-types/{slug}/edit',
        ['Dashboard/RoomTypes@edit_view', 'name' => 'room_types_edit']
    );
    Router::post('booking-dashboard/room-types/{slug}/edit',
        ['Dashboard/RoomTypes@edit', 'name' => 'room_types_edit']
    );
    Router::get('booking-dashboard/room-types/{slug}/delete/{id}',
        ['Dashboard/RoomTypes@deleteType', 'name' => 'room_types_delete']
    );
    Router::post('dashboard/room-types/upload',
        ['Dashboard/RoomTypes@upload']
    );
// Rooms
    Router::get('booking-dashboard/rooms/fetch',
        ['Dashboard/Rooms@fetch_rooms', 'name' => 'fetch_rooms']
    );
    Router::methods(['get', 'post'], 'booking-dashboard/rooms',
        ['Dashboard/Rooms@room_names', 'name' => 'room_names']
    );
    Router::methods(['get', 'post'], 'booking-dashboard/rooms/new',
        ['Dashboard/Rooms@new_room', 'name' => 'new_room']
    );
    Router::methods(['get', 'post'], 'booking-dashboard/rooms/edit-status',
        [
            'Dashboard/Rooms@edit_room_status',
            'name' => 'edit_room_status'
        ]
    );
    Router::methods(['get', 'post'], 'booking-dashboard/rooms/delete',
        ['Dashboard/Rooms@delete', 'name' => 'delete_room']
    );
//Amenities
    Router::methods(['get', 'post'], 'booking-dashboard/amenities',
        ['Dashboard/Amenities@amenities', 'name' => 'amenities']
    );
    Router::methods(['get', 'post'], 'booking-dashboard/amenities/new',
        ['Dashboard/Amenities@add', 'name' => 'amenities_add']
    );
    Router::methods(['get', 'post'], 'booking-dashboard/amenities/fetch',
        [
            'Dashboard/Amenities@fetch_amenities',
            'name' => 'fetch_amenities'
        ]
    );
    Router::methods(['get', 'post'], 'booking-dashboard/amenities/edit',
        ['Dashboard/Amenities@edit', 'name' => 'edit_amenities']
    );
    Router::post('booking-dashboard/amenities/delete',
        ['Dashboard/Amenities@delete', 'name' => 'delete_amenities']
    );
// Settings
    Router::get('booking-dashboard/settings',
        ['Dashboard/Settings@index', 'name' => 'settings']
    );
    Router::get('booking-dashboard/fetch_basic',
        ['Dashboard/Settings@fetch_basic', 'name' => 'fetch_basic']
    );
    Router::methods(['get', 'post'], 'booking-dashboard/template_email',
        [
            'Dashboard/Settings@template_email',
            'name' => 'template_email'
        ]
    );
    Router::methods(['get', 'post'], 'booking-dashboard/update_profile_settings',
        [
            'Dashboard/Settings@update_profile',
            'name' => 'update_profile_settings'
        ]
    );
    Router::methods(['get', 'post'], 'booking-dashboard/update_basic_settings',
        [
            'Dashboard/Settings@update_basic',
            'name' => 'update_basic_settings'
        ]
    );
// Reservations
    Router::get('booking-dashboard/reservations/pending',
        ['Dashboard/Reservations@pending', 'name' => 'pending']
    );
    Router::get('booking-dashboard/reservations',
        ['Dashboard/Reservations@reservations', 'name' => 'reservations_all']
    );
    Router::get('booking-dashboard/data_table',
        ['Dashboard/Reservations@data_table', 'name' => 'data_table']
    );
    Router::get('booking-dashboard/reservation/{id}',
        ['Dashboard/Reservations@reservation', 'name' => 'reservation']
    );
    Router::methods(['get', 'post'], 'booking-dashboard/reservation/edit-status',
        [
            'Dashboard/Reservations@reservationEditStatus',
            'name' => 'edit_status'
        ]
    );
    Router::methods(['get', 'post'], 'booking-dashboard/reservation/edit-date',
        [
            'Dashboard/Reservations@reservationEditDate',
            'name' => 'edit_date'
        ]
    );
    Router::post('booking-dashboard/reservation/fetch_room_names',
        ['Dashboard/Reservations@fetchAvailableRoomNames']
    );
    Router::post('booking-dashboard/reservation/attach_room',
        ['Dashboard/Reservations@attach_room']
    );
    Router::methods(['get', 'post'], 'booking-dashboard/reservation/delete/{id?}',
        [
            'Dashboard/Reservations@reservationDelete',
            'name' => 'reservation_delete'
        ]
    );
    Router::methods(['get', 'post'], 'booking-dashboard/reservation/cut_room',
        [
            'Dashboard/Reservations@reservationCutRoom',
            'name' => 'reservation_cut_room'
        ]
    );

    Router::methods(['get', 'post'], 'booking-dashboard/reservation/delete_room',
        [
            'Dashboard/Reservations@reservationDeleteRoom',
            'name' => 'reservation_delete_room'
        ]
    );

    Router::methods(['get', 'post'], 'booking-dashboard/reservation/add_room',
            ['Dashboard/Reservations@add_room']
    );

    // New reservation from Dashboard
    Router::methods(['get', 'post'], 'booking-dashboard/reservation/new',
        [
            'Dashboard/NewReservation@index',
            'name' => 'new_reservation_index'
        ]
    );
    Router::post('booking-dashboard/reservation/new/availRooms',
        ['Dashboard/NewReservation@availableRoomsForNewReservation']
    );
    Router::post('booking-dashboard/reservation/new/get_room_guest',
        ['Dashboard/NewReservation@get_room_guest']
    );
    Router::methods(['get', 'post'], 'booking-dashboard/reservation/new/add-room',
        ['Dashboard/NewReservation@add_room']
    );
    Router::get('booking-dashboard/reservation/new/update-added-room',
        ['Dashboard/NewReservation@update_added_room']
    );
    Router::post('booking-dashboard/reservation/new/delete-added-room',
        ['Dashboard/NewReservation@delete_added_room']
    );
    Router::methods(['get', 'post'], 'booking-dashboard/reservation/new/display-added',
        ['Dashboard/NewReservation@display_added']
    );
    Router::methods(['get', 'post'], 'booking-dashboard/reservation/new/new-reservation',
        ['Dashboard/NewReservation@new_reservation']
    );
// Prices
    Router::methods(['get', 'post'], 'booking-dashboard/seasons',
        [
            'Dashboard/Prices@seasons',
            'name' => 'seasons'
        ]
    );
    Router::methods(['get', 'post'], 'booking-dashboard/seasons/new',
        ['Dashboard/Prices@add_season', 'name' => 'add_season']
    );
    Router::methods(['get', 'post'], 'booking-dashboard/seasons/{id?}/edit',
        [
            'Dashboard/Prices@edit_season',
            'name' => 'edit_season'
        ]
    );
    Router::methods(['get', 'post'], 'booking-dashboard/seasons/update',
        [
            'Dashboard/Prices@update_season_price',
            'name' => 'update_season_price'
        ]
    );
    Router::methods(['get', 'post'], 'booking-dashboard/seasons/insert',
        [
            'Dashboard/Prices@insert_season',
            'name' => 'insert_season'
        ]
    );
    Router::get('booking-dashboard/seasons/{season_id}/delete',
        [
            'Dashboard/Prices@season_delete',
            'name' => 'season_delete']
    );

} catch (\Exception $e) {
    die($e->getMessage());
}


