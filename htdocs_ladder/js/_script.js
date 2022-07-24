$(()=>{

    window.plus = 150;
    window.state = false;
    window.canvas = document.getElementById('canvas');
    window.ctx = window.canvas.getContext('2d');
    $(document)
        .on('change','#directory_upload',load)
        .on('wheel','.list',move)
        .on('click','.search',search)
        .on('click','#play',play)
    draw();
})

function go(){
    window.logic = [];
    for(let i = 0; i < 14;i++){
        window.logic[i] = [];
        let number = [1,2,3,4,5];
        for (let a = 1; a <= 5;a++){
            const num = randomNum(a,number,window.logic[i]);
            window.logic[i].push(num);
            number = number.filter((e)=>e !== num);
        }
    }
    clear();
    draw();
    window.logic.forEach((e,idx,arr)=>{
        let block = (window.canvas.height / arr.length ) * idx +20;
        e.forEach((line,num)=>{
            if(line > num+1){
                window.ctx.beginPath();
                window.ctx.moveTo(window.plus / 2 + window.plus * num, block);
                window.ctx.lineTo(window.plus / 2 + window.plus * (num+1),block);
                window.ctx.stroke();
            }
            //num 인덱스
            //line 배열 값
        })
    })
    console.log(window.ladder,window.logic);
}

function clear(){window.ctx.clearRect(0,0,window.canvas.width,window.canvas.height)}

function randomNum(line,arr,ladder){
    const max = line+1;
    const min = line-1;
    let num = Math.floor(Math.random() * (max - min + 1)) + min;
    if(!arr.includes(num)) num +=1;
    if(!arr.includes(line)) num = line-1;
    if(num > 5) num = 5;
    return num;
}

function draw(){
    for( let i = 0; i < 5;i++){
        window.ctx.beginPath();
        window.ctx.moveTo(window.plus / 2 + window.plus * i, 0);
        window.ctx.lineTo(window.plus / 2 + window.plus * i,window.canvas.height);
        window.ctx.stroke();
    }
}

function play(){
    let data = $('.list').css('margin-left').split('px')[0] / 150;
    data = data < -0 ? data * -1 : data;
    let long = $('.box p');
    window.ladder = [];
    for (let i = 1; i <= 5;i++){
        let random = Math.random();
        window.ladder.push({ 'name' : $(long[Math.floor(random * (long.length - 0))]).attr('data-name'),'number' : i})
        long.splice(Math.floor(random * (long.length - 0)),1);
    }
    window.ladder.sort(() => Math.random() - 0.5);

    let question = '';
    let answer = '';
    window.ladder.forEach((e,idx)=>{
        question += `<img src="./flower/${e.name}.png" alt="" class="" onerror="this.src='./flower/${e.name}.jpg'" style="width: 150px;height: 150px;">`
        answer += `<div class="d-flex justify-content-center align-items-center" style="width: 150px;height: 150px;" data-idx="${idx}">
        <p>${e.name}</p>
    </div>`
    })
    $('.game_list').html(question);
    $('.ladder_list').html(answer);
    go();
}

function search(){
    if(window.state) return
    const val = $('#search').val();
    const data = $(`p[data-name="${val}"]`);
    if(!data[0]) return;
    let idx = data.attr('data-id')-2;
    if(idx < 0) idx = 0;
    if(idx+2 > 25) idx = 23;
    const value = idx * -plus;
    $('.list').css('margin-left',value);
}

function move(e){
    if(window.state) return
    const direction = e.originalEvent.wheelDelta > 0 ? 1 : -1;
    window.state = true;
    setTimeout(()=>{
        window.state = false;
    },600)
    const list  = $('.list');
    const margin_data = parseInt(list.css('margin-left'));

    if(margin_data + plus * direction > 0 || margin_data + plus * direction < -3450)return;
    list.css('margin-left',margin_data + plus * direction);
}

function load(){
    const input = document.querySelector('#directory_upload');
    const selectedFiles = input.files;
    let text = '';
    let idx = 0;
    for( let file of selectedFiles ){
        text += `
        <div class="box position-relative d-flex justify-content-center align-items-center">
        <img src="./flower/${file.name}" alt="" class="position-absolute">
        <p style="z-index: 3;background: #fff;" data-name="${file.name.split('.')[0]}" data-id="${idx}">${file.name.split('.')[0]}</p>
    </div>
        `
        idx++;
    }
    $('.list').html(text);
}