var path = window.location.href.split('/');
path = path[path.length-1].split('?')[0];
authenticate();

function authenticate(){
    if(sessionStorage.getItem('id_user') && sessionStorage.getItem('role') && sessionStorage.getItem('name')){
        if(sessionStorage.getItem('role') == 'admin'){
            if(path == 'index.html' || path == 'exam-live.html'){
                window.location.href = 'home.html';
            }
        }

        if(sessionStorage.getItem('role') == 'user'){
            if(path != 'index.html' && path != 'live-exam.html'){
                window.location.href = 'index.html';
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

    $.ajax(
        {
        url: server+'LoginController.php?ep=teacherLogin',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(resp){
            if(resp['status'] == 'OK'){
                sessionStorage.setItem('id_user', resp['id']);
                sessionStorage.setItem('name', resp['name']);
                sessionStorage.setItem('role', 'admin');
                window.location.href = 'home.html';
            }else{
                document.getElementById('teacher-alert').hidden = false;
            }
        }
    });
}

function register(event){
    if(event){
        event.preventDefault();
    }

    data = {};
    data['ais_id'] = document.getElementById('ais-id').value;
    data['email'] = document.getElementById('email').value;
    data['name'] = document.getElementById('name').value;
    data['surname'] = document.getElementById('surname').value;
    data['password'] = document.getElementById('password').value;
    data['passwordR'] = document.getElementById('password-again').value;

    $.ajax(
        {
        url: server+'LoginController.php?ep=teacherRegistration',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(resp){
            if(resp['status'] == 'OK'){
                sessionStorage.setItem('id_user', resp['id']);
                sessionStorage.setItem('role', 'admin');
                sessionStorage.setItem('name', resp['name']);
                window.location.href = 'home.html';
            }else{
                document.getElementById('register-alert').hidden = false;
            }
        }
    });
}

function joinExam(event){
    if(event){
        event.preventDefault();
    }

    data = {};
    data['code'] = document.getElementById('Scode').value;
    data['name'] = document.getElementById('Sname').value;
    data['surname'] = document.getElementById('Ssurname').value;
    data['ais_id'] = document.getElementById('Sais-id').value;

    if(data['ais_id'].length == 5 ){
        $.ajax(
            {
            url: server+'LoginController.php?ep=studentLogin',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(resp){
                if(resp['status'] == 'OK'){
                    sessionStorage.setItem('id_user', resp['id']);
                    sessionStorage.setItem('role', 'user');
                    sessionStorage.setItem('name', resp['name']);
                    sessionStorage.setItem('id_exam', resp['exam_id']);
                    window.location.href = 'live-exam.html?id_exam='+resp['exam_id'];
                }else{
                    document.getElementById('student-alert').hidden = false;
                } 
            }
        });
    }else{
        document.getElementById('student-alert').hidden = false;
    }
}

function logout(){
    sessionStorage.removeItem('id_user');
            sessionStorage.removeItem('role');
            sessionStorage.removeItem('name');
            //sessionStorage.removeItem('id_test');
            
            window.location.href = 'index.html';
    /*$.ajax(
        {
        url: server+'LoginController.php?ep=logout',
        type: 'POST',
        contentType: 'application/json',
        success: function(resp){
            sessionStorage.removeItem('id_user');
            sessionStorage.removeItem('role');
            sessionStorage.removeItem('name');
            //sessionStorage.removeItem('id_test');
            
            window.location.href = 'index.html';
        }
    });*/
}