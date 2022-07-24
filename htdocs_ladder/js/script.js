$(async ()=>{

    await load();
    $(document)
        .on('change','#file_upload',view)
        .on('mouseover','.hover_event',over)
        .on('mouseout','.hover_event',out)
        .on('click','.hover_event',click)
})


function click(){
    $('.e-view')[0].src = this.src;
}

function out(){
    this.pause();
    const time = setTimeout(';');
    for ( let i = 0; i < time; i++){
        clearTimeout(i);
    }
    $(this)[0].currentTime = 0;
}

function over(){
    for ( let i = 1; i <= 10;i++ ){
        setTimeout(()=>{
            const one = $(this)[0].duration / 10;
            $(this)[0].currentTime = one * i;
        },1000*i);
    }
}

function view(){
    const file = this.files[0];


    const video_data = URL.createObjectURL(file);

    let text = `<video src="${video_data}" autoplay id="video" style="width: 500px;height: 500px;"></video>`;
    $('#upload .modal-body').html(text);

    let html = `<video src="${video_data}" style="width: 300px;" class="hover_event" data-toggle="modal" data-target="#view"></video>`
    $('.file_list').append(html);
}

async function load(){
    const data = await fetch('./garden_data.json').then(res=>res.json());
    window.data = data['garden_data'];
    let text = '';
    window.data.forEach((e,idx)=>{
        text += `
            <div class="d-flex justify-content-between align-items-center">
                <img src="${e['image']}" alt="" style="width: 50px;height: 50px;margin-top: 10px;">
                <p>${e['name']}</p>
                <p>${e['introduce'].slice(0,30)}...</p>
                <button data-id="${idx}" class="btn btn-outline-primary free_view" onclick="free_view($(this).attr('data-id'))">상세보기</button>
            </div>`;
    })
    $('.list').html(text);
}

function free_view(id){
    let text =`
        <div class="data">
            <p class="m-0">${window.data[id]['name']}</p>
            <p class="m-0">${window.data[id]['introduce']}</p>
            <p class="m-0">${window.data[id]['institution']}</p>
            <p class="m-0">${window.data[id]['phone']}</p>
            <button class="btn btn-outline-primary" data-toggle="modal" data-target="#check">미디어업로드</button>
        </div>
        <img src="${window.data[id]['image']}" alt="">
    <div class="d-flex file_list">`;

    text += `</div>`
    $('.list').html('').css('width','0');
    $('.free_view').html(text);
}

function check(){
    const id = $('#id').val();
    const password = $('#password').val();
    $('.modal').modal('hide');
    if(id !== 'admin') return alert('관리자만 사용 가능한 기능입니다.');
    if(password !== '1234') return alert('관리자만 사용 가능한 기능입니다.');
    $('#upload .modal-body').html(` <div class="upload-container">
                        <input type="file" id="file_upload" multiple />
                    </div>`);
    $('#upload').modal('show');
}