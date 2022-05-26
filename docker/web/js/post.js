$(function() {

    const btnSubmit = document.getElementById('post-button');

    /**
     * 投稿追加モーダルのバリデーションチェック
     * 
     * @return void
     */
    function postValidaton(inputTitle,inputContent){
        let errors = [];
        //必須項目のチェック
        if(inputTitle =="" || inputContent ==""){
            errors.push("項目が未入力です。\n");
        }
        //投稿タイトルの文字数制限チェック
        if(inputTitle.length>20){
            errors.push("投稿タイトルを20文字以下で入力してください。\n");
        }
        //投稿内容の文字数制限チェック
        if(inputContent.length>200){
            errors.push("投稿タイトルを200文字以下で入力してください。\n");
        }
        //エラーが１つでもヒットしていたらエラー文表示
        if (errors.length>0) {
            return errors;
        }
    }

    //投稿するボタンを押した後の処理
    btnSubmit.addEventListener('click', function(event) {
        const inputTitle = document.getElementById('form-title').value;
        const inputContent = document.getElementById('form-content').value;
        const errors = postValidaton(inputTitle,inputContent);
        if (errors) {
            alert(errors);
            return;
        }
        $.ajax({
            type:'POST',
            url:'../php/ajax.php',
            datatype:'json',
            data:{
                'class':'postsTable',
                'func':'addPostData',
                'postTitle': inputTitle,
                'postContent': inputContent,
            },
            })
        .done(function(data){
                $('#post-modal').fadeOut();
                $('.sp-nav').fadeOut();
                $('#js-black-bg').fadeOut();
                getAddPostDataBase();
            }) 
        .fail(function (data){
            alert('通信失敗');
        })
    })

    /**
     *投稿一覧を表示する
     * 
     * @return void
     */
    function getPostDataBase(){
        $.ajax({
            type:'POST',
            url:'../php/ajax.php',
            datatype:'json',
            data:{
                'class':'postsTable',
                'func':'getPostData',
            },
            })
        .done(function(data){
            $.each(data,function(key,value){
                $('#post-data').append('<tr><td>'+'<input type="checkbox"></td><td>'+value.seq_no + '</td><td>' +value.user_id + '</td><td>' + value.post_date + '</td><td>'+ value.post_title +'<br>'+value.post_contents+'</td><td><i class="fa-solid fa-pen-to-square"></i></td><td>&times;</td></tr>'
        )});
        }).fail(function (data){
            alert('通信失敗');
        })
    }

    /**
     *新しい投稿を表示する
     * 
     * @return void
     */
    function getAddPostDataBase(){
        $.ajax({
            type:'POST',
            url:'../php/ajax.php',
            datatype:'json',
            data:{
                'class':'postsTable',
                'func':'getnewPostData',
            },
            })
        .done(function(data){
            $.each(data,function(key,value){
                $('#post-data').append('<tr><td>'+'<input type="checkbox"></td><td>'+value.seq_no + '</td><td>' +value.user_id + '</td><td>' + value.post_date + '</td><td>'+ value.post_title +'<br>'+value.post_contents+'</td><td><i class="fa-solid fa-pen-to-square"></i></td><td>&times;</td></tr>'
        )});
        }).fail(function (data){
            alert('通信失敗');
        })
    }

    //getPostDataBase関数の呼び出す
    getPostDataBase();

    // ハンバーガーメニュー
    const nav = document.getElementById('nav-wrapper');
    const hamburger = document.getElementById('js-hamburger');
    const blackBg = document.getElementById('js-black-bg');
    hamburger.addEventListener('click', function () {
        nav.classList.toggle('open');
    });
    blackBg.addEventListener('click', function () {
        nav.classList.remove('open');
    });

    //投稿追加を押した時の処理
    $('#add-post').click(function(){
        $('#post-modal').fadeIn();
    });
    $('#close-modal').click(function(){
        $('#post-modal').fadeOut();
    });  
});
