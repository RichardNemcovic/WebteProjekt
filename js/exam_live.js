var id_exam;

var qShort = [];
var qSelect = [];
var qImage = [];
var qMath = [];
var qPair = [];

var pairCount = {};
var stages = {};
var mathFields = {};

var questionIndex = 1; 
var container = document.getElementById('exam-form');

//IMPORTANT CHECK FOR TEST ID:
let url = new URL(window.location.href);
if(url.searchParams.has('id_exam')){
    id_exam = url.searchParams.get('id_exam');
}else{
    window.location.href = 'index.html';
}
//----------------------------
var server_time;

function get_server_time(callback){
    $.ajax(
        {
        url: server+'ExamController.php?ep=getServerTime',
        type: 'GET',
        contentType: 'application/json',
        success: function(resp){
            if(resp['status'] == 'OK'){
                server_time = new Date(resp['time']);
                callback();
            }
        },
        async: false
    });
}

awake();

var start_time;
var end_time;

function get_exam_times(callback){
    $.ajax(
        {
        url: server+'ExamController.php?ep=getExamTimes&id_exam='+id_exam,
        type: 'GET',
        contentType: 'application/json',
        success: function(resp){
            if(resp['status']=='OK'){
                start_time= new Date(resp['start']);
                end_time=new Date(resp['end']);
                
                if(callback){
                    callback();
                }
            }
        },
        async: false
    });
}

function awake(){
//    let start_time = new Date(question['exam']['start']);
    get_exam_times(function(){
        get_server_time(function(){
            if(start_time.getTime() - server_time.getTime() < 0){
                //getData from server
                
                document.getElementById('count-down').hidden = true;
                document.getElementById('live-exam').hidden = false;
                document.getElementById('live-exam-nav').hidden = false;
        
                $.get(server+'ExamController.php?ep=openExam&id_exam='+id_exam+'&id_user='+sessionStorage.getItem('id_user'), function(resp){
                    if(resp['status'] == 'OK'){
                        timer(resp['start']);    
                        setData(resp);      
                        setFocusListener();  
                    }
                }, false);
            }else{
                document.getElementById('count-down').hidden = false;
                document.getElementById('live-exam').hidden = true;
                document.getElementById('live-exam-nav').hidden = true;
        
                get_server_time(function(){
                    var myfunc = setInterval(function() {
                        var now = new Date().getTime();
                        let timeleft = start_time.getTime() - now;
                            
                        // Calculating the days, hours, minutes and seconds left
                        var days = Math.floor(timeleft / (1000 * 60 * 60 * 24));
                        var hours = Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((timeleft % (1000 * 60)) / 1000);
                            
                        // Result is output to the specific element            
                        document.getElementById("cDays").innerHTML = days;           
                        document.getElementById("cHours").innerHTML = hours; 
                        document.getElementById("cMins").innerHTML = minutes; 
                        document.getElementById("cSecs").innerHTML = seconds; 
                            
                        // Display the message when countdown is over
                        if (timeleft < 0) {
                            clearInterval(myfunc);
                            awake();
                        }
                    }, 1000);
                });
            }
        });
    });
}

function setFocusListener(){
    $(window).blur(function(){
        console.log('von');
    });
}

function timer(){
    get_exam_times(function(){
        let i = 0;

        var myfunc1 = setInterval(function() {
            i++;
            if(i%5==0){
                get_exam_times();
            }

            var now1 = new Date().getTime();
            var timeleft1 = end_time - now1;
                
            // Calculating the days, hours, minutes and seconds left
            var hours = Math.floor((timeleft1 % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((timeleft1 % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((timeleft1 % (1000 * 60)) / 1000);
                
            // Result is output to the specific element
            document.getElementById("cH").innerHTML = hours;
            document.getElementById("cM").innerHTML = minutes; 
            document.getElementById("cS").innerHTML = seconds; 
                
            // Display the message when countdown is over
            if (timeleft1 < 0) {
                clearInterval(myfunc1);
                submitTest();
            }
        }, 1000);
    });
    //let end_time = new Date(question['exam']['end']);    
}

function setData(data){
    document.getElementById('exam-name').innerHTML = data['name'];

    if(data['qShort']){
        data['qShort'].forEach(elem=>{
            createShort(elem);
        });
    }
    if(data['qSelect']){
        data['qSelect'].forEach(elem=>{
            createSelect(elem);
        });    
    }
    if(data['qEquation']){
        data['qEquation'].forEach(elem=>{
            createEquation(elem);
        });
    }
    if(data['qPairs']){
        data['qPairs'].forEach(elem=>{
            createPair(elem);
        });
    }
    if(data['qImage']){    
        data['qImage'].forEach(elem=>{
            createImage(elem);
        });
    }
}

//DYNAMIC HTML--------------------------------------
//SUCKS !!! DO NOT SCROLL DOWN!!!-------------------
function createShort(question){
    qShort.push(question['id']);

    let  div = document.createElement('div');
    div.className = 'card row m-4 py-3 px-5 shadow';
    div.innerHTML=`
    <div class="col" id="`+questionIndex+`">
        <div class="row">
            <div class="col-10">
                <h5>`+questionIndex+`.</h5>
                <h6>`+question['description']+`</h6>
            </div>
            <div class="col-2 text-end">
                <h5>`+question['score']+`b</h5>
            </div>                            
        </div>
        <hr>
        <div class="row">                                    
            <div class="col">
                <label for="answer-`+question['id']+`" class="form-label">Answer:</label>
                <input id="answer-`+question['id']+`" type="text" class="form-control"> 
            </div>                               
        </div> 
    </div>`

    container.appendChild(div);
    questionIndex++;
}

function createSelect(question){
    qSelect.push(question['id']);
    
    let div = document.createElement('div');
    div.className = 'card row m-4 py-3 px-5 shadow';
    let options = '<div class="col">'
    question['possibilities'].forEach(element => {
        options +=`
        <div class="form-check">
            <input class="form-check-input" type="radio" name="question-select-`+question['id']+`" id="question-select-`+question['id']+`-`+element['id']+`" value="`+element['id']+`">
            <label class="form-check-label" for="question-select-`+question['id']+`-`+element['id']+`" value="`+element['id']+`">`+element['answer']+`</label>
        </div>`
    });
    options += '</div>';

    div.innerHTML = `
    <div class="col" id="`+questionIndex+`">
        <div class="row">
            <div class="col-10">
                <h5>`+questionIndex+`.</h5>
                <h6>`+question['description']+`</h6>
            </div>
            <div class="col-2 text-end">
                <h5>`+question['score']+`b</h5>
            </div>                            
        </div>
        <hr>
        <div class="row">
        `+options+`
        </div>                                
        </div>
    </div>`;

    container.appendChild(div);
    questionIndex++;
}

function createPair(question){
    qPair.push(question['id']);

    let div = document.createElement('div');
    div.className = 'card row m-4 py-3 px-5 shadow';

    let options = '<div class="col">';
    let c = 1;
    pairCount[question['id']] = question['pairs'].length;
    question['pairs'].forEach(element => {
        options += `
        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="col-1 align-self-center">
                        <h5>`+c+`</h5>
                    </div>
                    <div class="col-11">
                        <div class="pair-tile" id="pair-l-`+question['id']+`-`+c+`">`+element['left']+`</div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col-2 align-self-center">
                        <input type="text" class="form-control" id="pair-r-`+question['id']+`-`+c+`">
                    </div>
                    <div class="col-10">
                        <div class="pair-tile" id="pair-r-`+question['id']+`-`+c+`-v">`+element['right']+`</div>
                    </div>
                </div>
            </div>
        </div>`;
        c++;
    });    

    div.innerHTML = `
    <div class="col" id="`+questionIndex+`">
        <div class="row">
            <div class="col-10">
                <h5>`+questionIndex+`.</h5>
                <h6>`+question['description']+`</h6>
            </div>
            <div class="col-2 text-end">
                <h5>`+question['score']+`b</h5>
            </div>                            
        </div>
        <hr>
        <div class="row">
        `+options+`
        </div>                                
        </div>
    </div>`;

    container.appendChild(div);
    questionIndex++;
}

function resetCanvas(canvasID, qID){
    document.getElementById(canvasID).innerHTML = '';

    stages['qID'] = createCanvas(canvasID);
}

function createImage(question){
    qImage.push(question['id']);

    let  div = document.createElement('div');
    div.className = 'card row m-4 py-3 px-5 shadow';
    div.innerHTML = `
    <div class="row">
        <div class="col-10">
            <h5>`+questionIndex+`.</h5>
            <h6>`+question['description']+`</h6>
        </div>
        <div class="col-2 text-end">
            <h5>`+question['score']+`b</h5>
        </div>                            
    </div>
    <hr>
    <div class="text-center">                                    
        <div id="container-`+question['id']+`" class="drawing-container text-center">

        </div>    
        <button class="btn bt-submit shadow d-inline-block text-center my-3 align-self-center" type="button" onclick="resetCanvas('container-`+question['id']+`',`+question['id']+`)">                                
            <span class="material-icons align-middle">restart_alt</span> Obnoviť plátno
        </button>
    </div>`;

    container.appendChild(div);
    questionIndex++;

    stages[question['id']] = createCanvas('container-'+question['id']);

    //console.log(stages[question['id']].toDataURL({ pixelRatio: 3 }));
}

function createEquation(question){
    qMath.push(question['id']);

    let div = document.createElement('div');
    div.className = 'card row m-4 py-3 px-5 shadow';
    div.innerHTML = `
    <div class="col">
        <div class="row">
            <div class="col-10">
                <h5>`+questionIndex+`.</h5>
                <h6>`+question['description']+`</h6>
            </div>
            <div class="col-2 text-end">
                <h5>`+question['score']+`b</h5>
            </div>                           
        </div>
        <hr>
        <div class="row">                    
            <div id="mathfield-`+question['id']+`" class="border-box">
            </div>                                        
        </div>                                
    </div>`;    

    container.appendChild(div);
    questionIndex++;

    mathFields[question['id']] = MathLive.makeMathField(document.getElementById('mathfield-'+question['id']),  {
        virtualKeyboardMode: "manual",
        virtualKeyboards: 'numeric symbols'
    });
}

//OBTAINING DATA

function submitTest(){
    let data = {};
    data['id_user'] = sessionStorage.getItem('id_user');
    
    let exam = {}
    exam['id'] = id_exam;
    exam['qShort'] = getShortAnswers();
    exam['qSelect'] = getSelectAnswers();
    exam['qImage'] = getImageAnswers();
    exam['qEquation'] = getEquationAnswers();
    exam['qPairs'] = getPairAnswers();
    
    data['exam'] = exam;

    $.ajax(
        {
        url: server+'ExamController.php?ep=submitExam',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(resp){
            if(resp['status'] == 'OK'){                
                document.getElementById('count-down').hidden = true;
                document.getElementById('live-exam').hidden = true;
                document.getElementById('live-exam-nav').hidden = true;
                document.getElementById('end').hidden = false;

                setTimeout(function() { logout(); }, 5000);
            }
        },
        async: false
    });
}

function getShortAnswers(){
    let data = [];
    qShort.forEach(e => {
        let d = {}
        d['id'] = e;
        d['answer'] = document.getElementById('answer-'+e).value;
        
        data.push(d);
    });

    return data;
}

function getSelectAnswers(){
    let data = [];

    qSelect.forEach(e=>{
        let d={};
        d['id'] = e;
        document.getElementsByName('question-select-'+e).forEach(el => {
            if(el.checked){
                d['id_answer'] = el.value;
            }
        });
        
        data.push(d);
    });

    return data;
}

function getPairAnswers(){
    let data = [];

    qPair.forEach(e => {
        let d = {};
        d['id'] = e;
        d['pairs'] = [];

        for(let i=1; i<=pairCount[e]; i++){
            let p = {};
            p['left'] = document.getElementById('pair-l-'+e+'-'+i).innerHTML;
            p['right'] = null;
            for(let j=1; j <= pairCount[e]; j++){
                if(document.getElementById('pair-r-'+e+'-'+j).value == i){
                    p['right'] = document.getElementById('pair-r-'+e+'-'+j+'-v').innerHTML;
                }
            }
            d['pairs'].push(p);
        }

        data.push(d);
    });

    return data;
}

function getEquationAnswers(){
    let data = [];

    qMath.forEach(e => {
        let d = {};
        d['id'] = e;
        d['answer'] = mathFields[e].getValue();

        data.push(d);
    });

    return data;
}

function getImageAnswers(){
    let data = [];

    qImage.forEach(e => {
        let d = {};
        d['id'] = e;
        d['image_data'] = stages[e].toDataURL({ pixelRatio: 3 });
        
        data.push(d);
    })

    return data;
}
