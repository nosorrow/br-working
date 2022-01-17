<?php

namespace App\Controllers\Dashboard;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

use Core\Libs\Request;
use Core\Libs\Session;
use Core\Libs\Validator;
use App\Models\DashboardRoom;
use Core\Libs\Files\Upload;
use Core\Libs\Files\ResponseFactory;
use Core\Libs\Images\Image;

/**
 * Class RoomTypes
 * @package App\Controllers\Dashboard
 */
class RoomTypes extends DashboardMainController
{
    protected $dashboardRoom;

    protected $uploader;

    protected $image;

    /**
     * RoomTypes constructor.
     * @param DashboardRoom $dashboardRoom
     * @param Upload $upload
     * @param Image $image
     */
    public function __construct(DashboardRoom $dashboardRoom, Upload $upload, Image $image)
    {
        parent::__construct();

        $this->view->setLayout('dashboard');
        $this->dashboardRoom = $dashboardRoom;
        $this->uploader = $upload;
        $this->image = $image;

    }

    public function roomTypes()
    {
        $data['types'] = $this->dashboardRoom->getAllRoomTypes();
        $this->view->render('Dashboard/room_types', $data);
    }

    public function newRoomType()
    {
        view('Dashboard/new');
    }

    public function add(Request $request, Validator $validator)
    {
        $validator->make('room_type', 'стая от тип', ['required'])
            ->make('slug', 'slug', ['required', 'unique:tbl_room_type.room_type_slug'])
            ->make('description', 'описание', ['required'])
            ->make('beds', 'beds', ['required'])
            ->make('adults', 'възрастни', ['required', 'integer', 'greater:0'])
            ->make('child', 'деца', ['required', 'integer', 'greater_equal:0'])
            ->make('guests', 'guests', ['required', 'integer'])
            ->make('price_weekday', 'price', ['required']);

        if ($validator->run() === false) {
            return $this->view->render('Dashboard/new');

        } else {
            //upload img
            $init = [
                'max_files' => 8,
                'directory' => 'images/roomtype/' . $request->post('slug') . '/',
                'preffix' => date('YmdH-i-s_', time()),
                'max_size' => 2,
                'file_name' => 'random',
            ];

            $this->uploader->init($init);

            $upload = $this->uploader->upload('files');
            $response = ResponseFactory::makeResponse($upload, 'html');

            //Upload images and write in DB
            // ако не е избрана снимка
            if ($upload->hasError() && $upload->getErrorCode() !== 4) {
                $request->session->setFlash('msg', $response->errors());
                view('Dashboard/new');

            } else {
                //ако не е избран файл - само запис в бд (url винаги е сериализиран масив)
                if ($upload->getErrorCode() == 4) {
                    $request->session->setFlash('msg', $response->errors());
                    $_i[] = 'images/no_image_available.jpeg';
                    $_img = serialize($_i);

                } else {
                    $files = $upload->getResponse();
                    //resize
                    foreach ($files as $key => $file) {
                        $this->image->get($file['file_path'])->resize(600)->save();
                        $_img_[] = $this->image->imageinfo('file_path');
                    }
                    $_img = serialize($_img_);
                }

                $data_tbl_room_type = [
                    'room_type' => $request->post('room_type'),
                    'room_type_slug' => $request->post('slug'),
                    'adults' => $request->post('adults', 'int'),
                    'child' => $request->post('child', 'int'),
                    'max_guests' => $request->post('guests', 'int'),
                    'price_weekday' => $request->post('price_weekday', 'int'),
                    'price_weekend' => $request->post('price_weekend', 'int'),
                    'quantity' => $request->post('units', 'int'),
                    'img_type_url' => $_img
                ];

                $amenities = $request->post('amenities', 'int');

                $insert = $this->dashboardRoom->table('tbl_room_type')->insert($data_tbl_room_type);

                $data_tbl_room_type_translate_bg = [
                    'room_type_id' => $insert['lastInsertId'],
                    'lang_code' => 'bg_BG',
                    'room_type' => $request->post('room_type'),
                    'description' => $request->post('description', 'html_entity_decode'),
                    'full_description' => $request->post('full_description', 'html_entity_decode'),
                    'beds' => $request->post('beds')
                ];
                $this->dashboardRoom->table('tbl_room_type_translate')->insert($data_tbl_room_type_translate_bg);

                $data_tbl_room_type_translate_en = [
                    'room_type_id' => $insert['lastInsertId'],
                    'lang_code' => 'en_US',
                    'room_type' => $request->post('room_type_en'),
                    'description' => $request->post('description_en', 'html_entity_decode'),
                    'full_description' => $request->post('full_description_en', 'html_entity_decode'),
                    'beds' => $request->post('beds_en')
                ];

                $this->dashboardRoom->table('tbl_room_type_translate')->insert($data_tbl_room_type_translate_en);

                if ($insert['rowCount'] > 0) {
                    $id = $insert['lastInsertId'];

                    foreach ($amenities as $amenitie) {
                        $this->dashboardRoom->table('tbl_room_amenities')
                            ->insert(['room_type_id' => $id, 'amenitie_id' => $amenitie]);
                    }

                    $request->session->setFlash('msg', 'Успешен запис !');

                    redirect(route('room_types', [], 'get'));
                }
            }

        }

    }

    /**
     * @param $slug
     */
    public function edit_view($slug)
    {
        $data['type'] = $this->dashboardRoom->getRoomType($slug);

        $room_id = $this->dashboardRoom->getId($slug);

        $data['amenitie_id'] = $this->dashboardRoom->amenitiesForRoom($room_id);

        view('Dashboard/new', $data);
    }

    /**
     * @param Request $request
     * @param Validator $validator
     * @param $slug
     * @throws \Core\Libs\Images\ImageException
     * @throws \Exception
     */
    public function edit(Request $request, Validator $validator, $slug)
    {
        $dir = PUBLIC_DIR . 'images/roomtype/' . $slug;

        $room_id = $this->dashboardRoom->getId($slug);

        $validator->make('room_type', 'стая от тип', ['required'])
            ->make('slug', 'slug', ['required', 'unique_except:tbl_room_type.room_type_slug.id.' . $room_id])
            ->make('description', 'описание', ['required'])
            ->make('beds', 'beds', ['required'])
            ->make('adults', 'възрастни', ['required', 'integer', 'greater:0'])
            ->make('child', 'деца', ['required', 'integer', 'greater_equal:0'])
            ->make('guests', 'guests', ['required', 'integer'])
            ->make('price_weekday', 'price', ['required']);

        if ($validator->run() === false) {
            return $this->view->render('Dashboard/new');

        } else {
            // ако има нови снимки - бършем рекурсивно старите
            $_files = $request->file()->getFile('files');

            if(!empty($_files['tmp_name'][0])){
               rrmdir($dir);
            }

            // качваме фото
            $init = [
                'max_files' => 8,
                'directory' => 'images/roomtype/' . $request->post('slug') . '/',
                'preffix' => date('YmdH-i-s_', time()),
                'max_size' => 1,
                'file_name' => 'random' //$request->post('slug'),
            ];

            $this->uploader->init($init);
            $upload = $this->uploader->upload('files');
            $response = ResponseFactory::makeResponse($upload, 'html');

            if ($upload->hasError() && $upload->getErrorCode() !== 4) {
                $request->session->setFlash('msg', $response->errors());
                redirect(route('room_types_edit', [$slug], 'get'));

            } else {
                //ако не е избран файл - само запис в бд
                if ($upload->getErrorCode() == 4) {
                    $request->session->setFlash('msg', $response->errors());
                    $url = $this->dashboardRoom->table('tbl_room_type')
                        ->field('img_type_url')
                        ->where('id', '=', $room_id)->get();
                    $_img = $url[0]->img_type_url;

                } else {
                    $files = $upload->getResponse();
                    //resize
                    foreach ($files as $key => $file) {
                        $this->image->get($file['file_path'])->resize(600)->save();
                        $_img_[] = $this->image->imageinfo('file_path');
                    }

                    $_img = serialize($_img_);
                }

                $data_tbl_room_type = [
                    'room_type' => $request->post('room_type'),
                    'room_type_slug' => $request->post('slug'),
                    'adults' => $request->post('adults', 'int'),
                    'child' => $request->post('child', 'int'),
                    'max_guests' => $request->post('guests', 'int'),
                    'price_weekday' => $request->post('price_weekday', 'int'),
                    'price_weekend' => $request->post('price_weekend', 'int'),
                    'quantity' => $request->post('units', 'int'),
                    'img_type_url' => $_img
                ];

                $data_tbl_room_type_translate_bg = [
                    'room_type' => $request->post('room_type'),
                    'description' => $request->post('description', 'html_entity_decode'),
                    'full_description' => $request->post('full_description', 'html_entity_decode'),
                    'beds' => $request->post('beds')
                ];

                $data_tbl_room_type_translate_en = [
                    'room_type' => $request->post('room_type_en'),
                    'description' => $request->post('description_en', 'html_entity_decode'),
                    'full_description' => $request->post('full_description_en', 'html_entity_decode'),
                    'beds' => $request->post('beds_en')
                ];

                $checkbox_amenities = $request->post('amenities', 'int');

                $update_tbl_room_type = $this->dashboardRoom->table('tbl_room_type')
                    ->where('id', '=', $room_id)
                    ->update($data_tbl_room_type);

                $update_tbl_room_type_translate_bg = $this->dashboardRoom->table('tbl_room_type_translate')
                    ->where([
                        ['room_type_id', '=', $room_id],
                        ['lang_code', '=', 'bg_BG']

                    ])->update($data_tbl_room_type_translate_bg);

                $update_tbl_room_type_translate_en = $this->dashboardRoom->table('tbl_room_type_translate')
                    ->where([
                        ['room_type_id', '=', $room_id],
                        ['lang_code', '=', 'en_US']

                    ])->update($data_tbl_room_type_translate_en);

                $am = $this->dashboardRoom->delete_room_amenities($room_id);

                foreach ($checkbox_amenities as $amenitie) {
                    $ins = $this->dashboardRoom->table('tbl_room_amenities')
                        ->insert(['room_type_id' => $room_id, 'amenitie_id' => $amenitie]);
                }

                if ($update_tbl_room_type || $am || $ins || $update_tbl_room_type_translate_bg || $update_tbl_room_type_translate_en) {

                    $request->session->setFlash('msg', 'Успешна редакция !');

                } else {

                    $request->session->setFlash('msg', 'Няма променен запис!');
                }

                redirect(route('room_types', [], 'get'));

            }

        }

    }

    public function deleteType(Request $request, $slug, $id)
    {
        $dir = PUBLIC_DIR . 'images/roomtype/' . $slug;

        $a = $this->table('tbl_rooms')->where('room_type_id', '=', $id)->delete();
        $b = $this->table('tbl_prices')->where('price_room_type_id', '=', $id)->delete();
        $c =  $this->table('tbl_room_type_translate')->where('room_type_id', '=', $id)->delete();
        $d = $this->table('tbl_room_amenities')->where('room_type_id', '=', $id)->delete();

        if ($this->table('tbl_room_type')->where('room_type_slug', '=', $slug)->delete()
            && rrmdir($dir)
        ) {

            $request->session->setFlash('msg',
                                'Записът е изртит: ' .'стаи: ' . $a .' цени: ' . $b.
                                ' преводи: '. $c. ' удобства: '.$d);
            redirect(route('room_types', [], 'get'));

        } else {
            throw new \Exception('Грешка при триенето', 500);
        }
    }

}