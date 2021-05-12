var server = "http://147.175.98.107/zaver/controller.php";

var path = window.location.href.split('/');
path = path[path.length-1];
authenticate();

function authenticate(){
    if(sessionStorage.getItem('id_user') && sessionStorage.getItem('role') && sessionStorage.getItem('name')){
        if(path == 'index.html'){
            if(sessionStorage.getItem('id_test')){
                window.href.location = 'exam.html';
            }else{
                window.href.location = 'home.html';
            }
        }
    }else{
        if(path != 'index.html' && path != 'register.html'){
            window.location.href = 'index.html';
        }
    }
}

function login(event){
    if(event){
        event.preventDefault();
    }

    data = {};
    data['email'] = document.getElementById('Temail').value;
    data['password'] = document.getElementById('Tpassword').value;

    $.post(server+'?ep=teacherLogin', data, function(resp){
        if(resp['status'] == 'OK'){
            sessionStorage.setItem('id_user', resp['id']);
            sessionStorage.setItem('name', resp['name']);
            sessionStorage.setItem('role', 'admin');
            window.location.href = 'home.html';
        }else{
            document.getElementById('teacher-alert').hidden = false;
        }
    });
}

function register(event){
    if(event){
        event.preventDefault();
    }

    data = {};
    data['aisID'] = document.getElementById('ais-id').value;
    data['email'] = document.getElementById('email').value;
    data['name'] = document.getElementById('name').value;
    data['surname'] = document.getElementById('surname').value;
    data['password'] = document.getElementById('password').value;
    data['passwordR'] = document.getElementById('password-again').value;

    $.post(server+'?ep=registration', data, function(resp){
        if(resp['status'] == 'OK'){
            sessionStorage.setItem('id_user', resp['id']);
            sessionStorage.setItem('role', 'admin');
            sessionStorage.setItem('name', resp['name']);
            window.location.href = 'home.html';
        }else{
            document.getElementById('register-alert').hidden = false;
        }
    });
}

function joinExam(event){
    if(event){
        event.preventDefault();
    }

    data = {};
    data['kod'] = document.getElementById('Scode').value;
    data['name'] = document.getElementById('Sname').value;
    data['surname'] = document.getElementById('Ssurname').value;
    data['aisID'] = document.getElementById('Sais-id').value;

    $.post(server+'?ep=studentLogin', data, function(resp){
        if(resp['status'] == 'OK'){
            sessionStorage.setItem('id_user', resp['id']);
            sessionStorage.setItem('role', 'user');
            sessionStorage.setItem('name', resp['name']);
            sessionStorage.setItem('id_test', resp['id_test']);
            window.location.href = 'exam.html';
        }else{
            document.getElementById('student-alert').hidden = false;
        }
    });
}

function logout(){
    $.post(server+'?ep=logout', function(data){
        sessionStorage.removeItem('id_user');
        sessionStorage.removeItem('role');
        sessionStorage.removeItem('name');
        sessionStorage.removeItem('id_test');
        
        window.location.href = 'index.html';
    });
}