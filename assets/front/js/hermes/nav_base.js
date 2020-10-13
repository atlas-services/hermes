/*
    css
 */
import '../../css/hermes/base.css';
import '../../css/navbar/base.css';

/*
    js
 */
import '../hermes/scroll';
import '../hermes/stickyfy';

$('.dropdown')
    .on('show.bs.dropdown', function() {
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
    })
    .on('hide.bs.dropdown', function() {
        $(this).find('.dropdown-menu').first().stop(true, false).fadeOut(150, function() {
            $(this).parent().removeClass('open');
        });
    })
    .on('hidden.bs.dropdown', function() {
        $(this).addClass('open');
    });
