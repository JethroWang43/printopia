<?php
namespace App\Controllers;

class Users extends BaseController {
    public function index() {
        $usermodel = model('Users_model');
        $session = session();

        $data = array(
            'title' => 'TW32 App - Welcome',
            'users' => $usermodel->paginate(2),
            'pager' => $usermodel->pager
        );

        return view('include\head_view', $data)
            .view('include\nav_view')
            .view('userslist_view', $data)
            .view('include\foot_view')
            .view('include\close_view');
    }

    public function add() {
        $session = session();
        $data = array(
            'title' => 'TW32 App - Add New User',
        );

        return view('include\head_view', $data)
            .view('include\nav_view')
            .view('adduser_view')
            .view('include\foot_view')
            .view('include\close_view');
    }

    public function insert() {
        $usermodel = model('Users_model');
        // Creates the session object
        $session = session(); // $session = service('session');

        // Creates and loads the Validation library
        $validation = service('validation');

        $data = array (
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'confirmpassword' => $this->request->getPost('confirmpassword'),
            'fullname' => $this->request->getPost('fullname'),
            'email' => $this->request->getPost('email'),
        );

        // Runs the validation
        if(! $validation->run($data, 'signup')){
            // If validation fails, reload the form passing the error messages
            $data = array(
                'title' => 'TW32 App - Add New User',
                // 'errors' => $validation->getErrors()
            );
            // Set the flash data session item for the errors
            $session->setFlashData('errors', $validation->getErrors());

            // return view('include\head_view', $data)
            //     .view('include\nav_view')
            //     .view('adduser_view', $data)
            //     .view('include\foot_view');
            return redirect()->to('users/add');
        }

        $usermodel->insert($data);
        $session->setFlashData('success', 'Adding new user is successful.');

        return redirect()->to('users');
    }

    public function view($id) {
        $usermodel = model('Users_model');

        $data = array(
            'title' => 'TW32 App - View User Record',
            'user' => $usermodel->find($id)
        );

        return view('include\head_view', $data)
            .view('include\nav_view')
            .view('viewuser_view', $data)
            .view('include\foot_view')
            .view('include\close_view');
    }

    public function edit($id) {
        $usermodel = model('Users_model');
        $session = session();

        $data = array(
            'title' => 'TW32 App - View User Record',
            'user' => $usermodel->find($id)
        );

        return view('include\head_view', $data)
            .view('include\nav_view')
            .view('updateuser_view', $data)
            .view('include\foot_view')
            .view('include\close_view');
    }

    public function update($id) {
        $usermodel = model('Users_model');
        $session = session();

        $data = array (
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'fullname' => $this->request->getPost('fullname'),
            'email' => $this->request->getPost('email'),
        );

        $usermodel->update($id, $data);

        return redirect()->to('users');
    }

    public function delete($id) {
        $usermodel = model('Users_model');
        $session = session();
        $usermodel->delete($id);
        return redirect()->to('users');
    }
}
?>