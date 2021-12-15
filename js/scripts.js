
(function($) {
    $(function(){

        ajaxContent();
        $(".header li").on("click", ajaxContent);

        function ajaxContent(){

            let id = $(this).attr('id');

            console.log(id);
            if(id){
                $(".header li").removeClass("active");
                $(this).addClass("active");
            }

            let data = {
                'action': 'get_casestudies',
                'type': id
            }

            $.ajax({
                url: '/hmmhfront/wp-admin/admin-ajax.php',
                type: 'POST',
                data: data,
                success: function (response) {

                    let result = $.parseJSON(response);
                    let html = ``;

                    result.forEach(function(item) {

                        html += `<section class="col-md-6">
                                    <div class="row case-studies">
                                        <div class="col-md-12 case-studies__container">
                                            <img src="${item.image}" class="case-studies__container__image">
                                            <div class="cs-textbox">
                                                <p class="cs-textbox__tags">${item.tag}</p>
                                                <p class="cs-textbox__title">${item.title}</p>
                                                <p class="cs-textbox__link"><a class="cs-textbox__link--href"" href="${item.link.url}"><span>${item.link.title}</span></a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </section>`;

                    });
                    $("#container").html(html);
                }
            })
        }
    })
}(jQuery));