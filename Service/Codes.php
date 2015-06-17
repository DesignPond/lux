<?php

class Codes{

    public function codeIsValid($code,$date)
    {
        $thisyearnumber = date('y');
        $thisdaydate    = date('Y-m-d');

        // Get 2 first digit from code
        $digit = substr($code, 0, 2);

        if( $digit >= $thisyearnumber && $date > $thisdaydate)
        {
            return true;
        }

        return false;
    }

    public function dateIsValid($date){

        $thisdaydate = date('Y-m-d');

        if($date > $thisdaydate)
        {
            return true;
        }

        return false;
    }

    public function getAll($currentDate = null)
    {
        global $wpdb;

        $query = 'SELECT * from wp_code WHERE validity_code';

        if($currentDate)
        {
            $query .= ' BETWEEN "'.$currentDate.'-01-01" AND "'.$currentDate.'-12-31" ';
        }

        $query .= 'ORDER BY number_code ASC,validity_code ASC';

        return $wpdb->get_results($query);
    }

    public function getCode($id)
    {
        global $wpdb;

        return $wpdb->get_results($wpdb->prepare("SELECT * from wp_code where id_code=%s",$id));
    }

    public function create($data)
    {

        global $wpdb;

        $params =[
            'number_code'   => $data['number_code'],
            'validity_code' => $data['validity_code'],
            'valid_code'    => (isset($data['valid_code']) ? $data['valid_code'] : 1),
            'updated'       => (isset($data['updated']) ? $data['updated'] : '0000-00-00'),
            'user_id'       => (isset($data['user_id']) ? $data['user_id'] : 0),
        ];

        return $wpdb->insert('wp_code', $params);

    }

    public function update($data, $id)
    {

        global $wpdb;

        $params = [
            'number_code'   => $data['number_code'],
            'validity_code' => $data['validity_code'],
            'valid_code'    => (isset($data['valid_code']) ? $data['valid_code'] : 1),
            'updated'       => (isset($data['updated']) ? $data['updated'] : '0000-00-00'),
            'user_id'       => (isset($data['user_id']) ? $data['user_id'] : 0),
        ];

       return $wpdb->update(
            'wp_code', //table
            $params, //data
            array( 'id_code' => $id ), //where
            array('%d','%s','%d','%s','%d'), array('%d') //data format
        );
    }

    public function delete($id)
    {
        global $wpdb;

        return $wpdb->query($wpdb->prepare("DELETE FROM wp_code WHERE id_code = %s",$id));
    }
}