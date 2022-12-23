$(document).ready(function() {
    Notiflix.Loading.init({
        backgroundColor: 'rgba(255,255,255,0.5)',
        svgColor: '#A40000',
        clickToClose: false,
    });

    $('form').each(function() {
        $(this).on('submit', function(e) {
            e.preventDefault()

            const requestUrl = $(this).attr('action')
            const requestMethod = $(this).attr('method')
            let requestFormData = $(this).attr('id')
            let formData = new FormData($('#' + requestFormData)[0])
            Notiflix.Loading.standard();

            $.ajax({
                type: requestMethod,
                url: requestUrl,
                dataType: 'JSON',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    // console.log('Request Success')
                    // console.log(response)
                    Notiflix.Loading.remove();
                    if (response.status) {
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: "D'accord",
                            confirmButtonColor: '#A40000',
                        }).then((action) => {
                            Notiflix.Loading.pulse();
                            setTimeout(() => {
                                if (response.redirect_to != null) {
                                    window.location.assign(response.redirect_to)
                                } else {
                                    window.location.reload()
                                }
                            }, 1500)
                        })
                    } else {
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: "D'accord",
                            confirmButtonColor: '#A40000',
                        })
                    }
                },

                error: (error) => {
                    // console.log('Request Error')
                    // console.log(error)
                    Notiflix.Loading.remove();

                    Swal.fire({
                        title: 'Oops',
                        text: "Nous sommes désolés pour la gêne occasionnée. Impossible de communiquer avec les serveurs de Youpigoo",
                        icon: 'error',
                        confirmButtonText: "D'accord",
                        confirmButtonColor: '#A40000',
                    })
                }
            })
        })
    })
})