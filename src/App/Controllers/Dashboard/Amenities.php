<?php

namespace App\Controllers\Dashboard;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

use Core\Libs\Request;
use Core\Libs\Session;
use App\Models\DashboardRoom;

/**
 * Class Amenities
 * @package App\Controllers\Dashboard
 */
class Amenities extends DashboardMainController
{
    public $dashboardRoom;

    /**
     * Amenities constructor.
     * @param DashboardRoom $dashboardRoom
     */
    public function __construct(DashboardRoom $dashboardRoom)
    {
        parent::__construct();
        $this->view->setLayout('dashboard');
        $this->dashboardRoom = $dashboardRoom;
    }

    public function amenities()
    {
        $data['amenities'] = $this->dashboardRoom->table('tbl_amenities')->get();

        return view('Dashboard/amenities', $data);
    }

    public function fetch_amenities()
    {
       // $amenities = $this->dashboardRoom->table('tbl_amenities')->get();
        $amenities = $this->dashboardRoom->fetch_amenities_model();
        $output = '<table class="table table-striped">
                    <tr>
                        <th>иконка</th>
                        <th>font awesome name</th>
                        <th>удобство</th>
                        <th>англйски</th>
                        <th>действие</th>
                    </tr>';

        foreach ($amenities as $amenitie) {
            $output .= '<tr>
                            <td><i class="fa ' . $amenitie->icon .'" aria-hidden="true"></i></td>
                            <td class="icon" data-icon_id="' . $amenitie->id . '" contenteditable="true">' . $amenitie->icon . '</td>
                            <td class="amenities" data-amenities_id="' . $amenitie->id . '" contenteditable="true">' . $amenitie->name . '</td>
                            <td class="amenities_en" data-amenities_id_en="' . $amenitie->id . '" contenteditable="true">' . $amenitie->en_name . '</td>
                            <td>
                            <dutton id="btn_delete" data-delete_id="' . $amenitie->id . '"
                            class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Изтрий">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                            </dutton>
                            </td>
                        </tr>';
        }

        $output .= '<tr>
                        <td></td>
                        <td id="icon" contenteditable="true"></td>
                        <td id="amenities" contenteditable="true" class="has-error"></td>
                        <td id="amenities_en" contenteditable="true" class="has-error"></td>
                        <td>
                        <dutton id="btn_add" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Натисни за да добавиш">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        </dutton>
                        </td>
                    </tr>
                </table>';

        echo $output;

    }

    /**
     * @param Request $request
     */
    public function add(Request $request)
    {
        $amenities_bg = $request->post('amenities');
        $amenities_en = $request->post('amenities_en');

        $ins = [
            'name' =>$amenities_bg,
            'icon' => $request->post('icon'),
            'icon_tag'=>'<i fa '. $request->post('icon') . '" aria-hidden="true"></i>'
        ];

        $insert = $this->dashboardRoom->table('tbl_amenities')->insert($ins);
        if ($insert) {
            $amenities_id = $insert['lastInsertId'];

            $dataBG = [
                'amenitie_id'=>$amenities_id,
                'lang_code_id'=>1,
                'translate_name'=>$amenities_bg
            ];

            if($this->dashboardRoom->table('tbl_amenities_translate')->insert($dataBG)){
                $dataEN = [
                    'amenitie_id'=>$amenities_id,
                    'lang_code_id'=>2,
                    'translate_name'=>$amenities_en
                ];

                $this->dashboardRoom->table('tbl_amenities_translate')->insert($dataEN);

                echo alert('success', 'Успешен запис');

            }
        }
    }

    public function edit(Request $request)
    {
        $id = $request->post('id');

        if ($request->post('column') == 'icon'){
            $up = [
                $request->post('column') => $request->post('text'),
                'icon_tag'=>'<i class="fa '. $request->post('text') . '" aria-hidden="true"></i>'

            ];

            if ($this->dashboardRoom->table('tbl_amenities')->where('id', '=', $id)->update($up)) {
                echo alert('success', 'Успешна редакция');
            }

        } elseif ($request->post('column') == 'name'){
            $up = [
                $request->post('column') => $request->post('text')
            ];

            if ($this->dashboardRoom->table('tbl_amenities')->where('id', '=', $id)->update($up)) {
                $this->dashboardRoom->table('tbl_amenities_translate')
                    ->where([
                        ['amenitie_id', '=', $id],
                        ['lang_code_id', '=', 1]
                    ])->update(['translate_name'=>$request->post('text')]);
                echo alert('success', 'Успешна редакция');
            }

        } else {
            // update en_name
            if ($this->dashboardRoom->table('tbl_amenities_translate')
                ->where([
                    ['amenitie_id', '=', $id],
                    ['lang_code_id', '=', 2]
                ])->update(['translate_name'=>$request->post('text')])>0) {

                echo alert('success', 'Успешна редакция');
            }
        }

    }

    /**
     * @param Request $request
     * @return int|void
     */
    public function delete(Request $request)
    {
        $id = $request->post('id');

        if($this->dashboardRoom->table('tbl_amenities')->where('id', '=', $id)->delete()>0){

            if($this->dashboardRoom->table('tbl_amenities_translate')->where('amenitie_id', '=', $id)->delete()>0){
               print ("Успешно изтрихте записа!");

            } else {
                print ('Нещо се обърка');
            }
        }
    }

}