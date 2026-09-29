<?php 
//is_unique[table_name.field_to_check.id_field_name.id_field_value] <-- unique 1 field only
//is_unique[table_name.field_to_check.id_field_name != 'id_field_value' and anotherIdName='theValue'] <-- custom where 
class MY_Form_validation extends CI_Form_validation {
    public function is_unique($str, $field)
    {
        $hasil = true;

        try {
            //hanya validasi jika ada isinya. jika tidak ada isinya, tidak usah divalidasi.
            //karena kalau memang tidak boleh kosong, bisa kasih validasi required
            if ($str) {
                $x = substr_count($field, '.');
                if(strpos($field, "=") > 0) {
                    list($table, $field, $where) = explode('.', $field);
//                    list($table, $field, $id_field, $id_val, $where) = explode('.', $field);

                    $is_unique = 0;

                    if ($where) {
                        $logos = "select * from $table where $field =? and $where ";
                    } else {
                        $logos = "select * from $table where $field =?  ";
                    }

                    $data = array($str);
                    $qq = $this->CI->db->query($logos, $data);

                    if (is_log_query()) {
                        $logos = $this->CI->db->last_query();
                        log_to_file($logos);
                    }

                    $row = $qq->row();
                    if ($row) {
                        if ($row->id) {
//                            if ($row->$id_field == $id_val) {
//                                $is_unique = 1; //berarti jika edit miliknya sendiri
//                            } else {
//                                //berarti id milik record lain
//                            }
                        } else {
                            //berarti sudah ada di xuser_confirmed
                        }
                    } else {
                        $is_unique = 1; //belum ada sama sekali
                    }

                    $hasil = (bool)$is_unique;

                }
                else {
                    if ($x >= 3) {
                        list($table, $field, $id_field, $id_val) = explode('.', $field);

                        $is_unique = 0;

                        if ($id_field && $id_val) {
                            $logos = "select * from $table where $field =? and $id_field != '$id_val' ";
                        } else {
                            $logos = "select * from $table where $field =?  ";
                        }

                        $data = array($str);
                        $qq = $this->CI->db->query($logos, $data);

                        if (is_log_query()) {
                            $logos = $this->CI->db->last_query();
                            log_to_file($logos);
                        }

                        $row = $qq->row();
                        if ($row) {
                            if ($row->id) {
                                if ($row->$id_field == $id_val) {
                                    $is_unique = 1; //berarti jika edit miliknya sendiri
                                } else {
                                    //berarti id milik record lain
                                }
                            } else {
                                //berarti sudah ada di xuser_confirmed
                            }
                        } else {
                            $is_unique = 1; //belum ada sama sekali
                        }

                        $hasil = (bool)$is_unique;


                    } 
                    else if ($x == 1) {
                        list($table, $field) = explode('.', $field);
                        $query = $this->CI->db->limit(1)->get_where($table, array($field => $str));
                        $hasil = $query->num_rows() === 0;
                    }
                }
            }

        }catch (Exception $e) {
            die($e->getTraceAsString());
        }
        return $hasil;
    }
}

?>