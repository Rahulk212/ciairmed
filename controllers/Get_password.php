<?php

class Get_password extends CI_Controller {

    public function index($rs = FALSE) {
        $this->load->database();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|matches[password]');
        if ($this->form_validation->run() == FALSE) {
            echo form_open();
            $this->load->view('gp_form');
        } else {
            $code = $this->uri->segment('3');
            $query = $this->db->get_where('admin_master', array('rs' => $code), 1);
            if ($query->num_rows() == 0) {
                show_error('Sorry!!! Invalid Request!');
            } else {
                $pass = $this->input->post('password');
                $cpass = $this->input->post('passconf');
                if ($pass == $cpass) {
                    $data = array(
                        'password' => $this->input->post('password'),
                        'rs' => ''
                    );
                    $where = $this->db->where('status', '1');
                    //$where=$this->db->where('type','1');	
                    $where = $this->db->where('rs', $code);
                    $where->update('admin_master', $data);

                    $this->session->set_userdata('getmsg', array("Congratulations!!! Your Password is Changed!"));
                    redirect('login', 'refresh');
                } else {
                    echo "<script>alert('Password Do Not Match!')</script>";
                }
            }
        }
    }

}
