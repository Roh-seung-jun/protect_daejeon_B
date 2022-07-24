$(async ()=>{
    let getData = await fetch('./garden_data.json').then(res=>res.json());
    window.data = getData.garden_data;
    let text = '';
    console.log(window.data);
    window.data.forEach((item,idx)=>{
        text += `<div class="box w-25 m-2 text-center p-4" data-id="${idx}">
                <img src="${item['image']}" alt="" style="width: 150px;height: 150px; object-fit: cover;border-radius: 30px">
                <h4 class="m-1">${item['name']}</h4>
                <p>${item['introduce'].slice(0,100)}</p>
            </div>`;
    })
    $('.list').html(text);


    $(document)
        .on('click','.box',view)
        .on('click','.login',login)
})


function login(){
    let id = $('#id').val();
    let password = $('#password').val();
    if(id !== 'admin' || password !== '1234') return alert('아이디 또는 비밀번호를 확인해주세요.');
    $('.modal-body').html(`
                <div class="form-group">
                    <p class="m-0">파일을 업로드해주세요.</p>
                    <input type="file" accept=".jpg .png .mp4" name="file" id="file">
                </div>`)
}


function view(){
    let idx = $(this).attr('data-id');
    $('.list').html('');
    let text = `
            <img src="${window.data[idx]['image']}" alt="" style="width: 400px;height: 400px;object-fit: cover;border-radius:10px ">
            <h1 class="mt-4">${window.data[idx]['name']}</h1>
            <p>${window.data[idx]['phone']}</p>
            <p>${window.data[idx]['introduce']}</p>
            <p>${window.data[idx]['institution']}</p>
            <div class="thumbnail d-flex">
            
</div>            
<button class="btn btn-outline-info" data-toggle="modal" data-target="#upload">미디어 업로드</button>
`
    $('.title').html('');
    $('.view').html(text);
}