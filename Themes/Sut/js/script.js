var config, gen_icon_to_page_status_line, gen_li_to_page_status_line, gen_speed_link,
    gen_speed_target, get_article, get_menu, init_article, load_more_article, ls,
    modal_call, randomNumber, render_article_short, reset_disqus, run_all_run,
    setCommentCount, set_article, set_config, set_menu, set_title, setup_banner,
    show_article, urlParser, dates_events;

config = window.config;

ls = window.ls = window.localStorage;

set_config = function(callback) {
  $.ajax({
    url: 'json/config.json',
    cache: false,
    type: 'GET',
    dataType: 'json',
    async: true,
    success: function(data, status, jqhxr) {
      var location_host;
      location_host = 'http://' + location.host;
      config = data;
      config.disqus_url = location_host + '/#' + data.disqus_url_v + '_';
      config.disqus_url_short = location_host + '/#' + data.disqus_url_v + '_';
      config.disqus_url_verb = data.disqus_url_v + '_';
      if (callback) {
        callback();
      }
    }
  });
};

set_menu = function(data, menu) {
  var fn, i, j, len, menu_raw, ul;
  menu_raw = data.menu.content.trim().split('\n');
  ul = $(menu);
  fn = function(i) {
    var li, link, link_target, link_text;
    li = document.createElement('li');
    $(li).attr('role', 'presentation');
    link_text = i.split(':')[0];
    link_target = '../' + i.split(':')[1] + '.html';
    link = document.createElement('a');
    $(link).text(link_text);
    $(link).attr('href', link_target);
    $(li).append(link);
    $(ul).append(li);
  };
  for (j = 0, len = menu_raw.length; j < len; j++) {
    i = menu_raw[j];
    fn(i);
  }
};

gen_speed_link = function(href, text) {
  var link;
  link = document.createElement('a');
  $(link).attr('href', href).text(text).attr('alt', text).attr('class', 'btn btn-default');
  return link;
};

gen_speed_target = function(name) {
  var target;
  target = document.createElement('a');
  $(target).attr('href', '').text('').attr('alt', '').attr('name', name);
  return target;
};

setCommentCount = function() {
  DISQUSWIDGETS.forum = 'sutvdonsk-ru';
  DISQUSWIDGETS.getCount();
};

reset_disqus = function(article_id, article_name) {
  var disqus_identifier, disqus_title, disqus_url;
  disqus_identifier = config.disqus_ident + article_id;
  disqus_url = config.disqus_url + article_id;
  disqus_title = article_name;
  DISQUS.reset({
    reload: true,
    config: function() {
      this.page.identifier = disqus_identifier;
      this.page.url = disqus_url;
      this.page.title = disqus_title;
    }
  });
  setTimeout(setCommentCount, 1100);
};

get_article = function(article_id) {
  $.ajax({
    url: '../jsondbase/' + article_id + '.json',
    cache: false,
    type: 'GET',
    dataType: 'json',
    async: true,
    success: function(data, status, jqhxr) {
      show_article(data);
    }
  });
};

show_article = function(data) {
  var article_id, comment_count, fn, frame, gitem, img, item, items_gallery, j, len, owl_div, p, speed_link, wrap_area, wrap_header;
  item = data;
  wrap_area = $('.modal_page__content');
  wrap_header = $('.modal_page__header_content');
  $(wrap_area).empty();
  $(wrap_header).empty();
  if (item) {
    article_id = data.uid.content.trim();
    history.pushState(null, null, config.disqus_url + article_id);
    if (item.title) {
      $(wrap_header).append(gen_speed_link('#page_text', 'текст'));
      $(wrap_area).append(gen_speed_target('page_text'));
      $(wrap_area).append(marked(item.title.content));
    }
    if (item.time) {
      $(wrap_area).append($('<strong>').text(item.time.content.trim()));
    }
    if (item.picture) {
      img = marked(item.picture.content);
      img = $(img).attr('class', 'first');
      $(wrap_area).append(img);
    }
    if (item.fulldesc) {
      $(wrap_area).append(marked(item.fulldesc.content));
    }
    if (item.video) {
      p = document.createElement('p');
      $(p).attr('class', 'for_video');
      frame = document.createElement('iframe');
      $(frame).attr('width', '640').attr('height', '360').attr('frameborder', '0').attr('src', 'https://' + item.video.content.trim().split('://')[1]);
      $(p).append(frame);
      $(wrap_area).append(p);
    }
    if (item.gallery) {
      items_gallery = $(marked(item.gallery.content)).find('img');
      speed_link = document.createElement('a');
      $(speed_link).attr('name', 'gallery_area').attr('href', '#').text('');
      owl_div = document.createElement('div');
      fn = function(gitem) {
        var img_item, src;
        img_item = document.createElement('div');
        $(img_item).attr('class', 'item');
        src = $(gitem).attr('src');
        $(gitem).attr('src', '').attr('class', 'lazyOwl').attr('data-src', src);
        $(img_item).append(gitem);
        $(owl_div).append(img_item);
      };
      for (j = 0, len = items_gallery.length; j < len; j++) {
        gitem = items_gallery[j];
        fn(gitem);
      }
      $(owl_div).owlCarousel({
        items: 1,
        lazyLoad: true,
        navigation: true,
        slideSpeed: 500,
        paginationSpeed: 2500,
        singleItem: true,
        autoPlay: false,
        navigationText: ["", ""],
        pagination: true
      });
      $(wrap_header).append(gen_speed_link('#gallery', 'фотографии'));
      $(wrap_area).append(gen_speed_target('gallery'));
      $(wrap_area).append(owl_div);
    }
    if (item.comments && item.comments.value === true) {
      $('#disqus_thread').show();
      article_id = item.uid.content.trim();
      $(wrap_area).append(gen_speed_target('comments'));
      comment_count = document.createElement('a');
      $(comment_count).attr('href', '#comments').attr('class', 'btn btn-default disqus-comment-count').attr('data-disqus-identifier', config.disqus_ident + article_id).attr('data-disqus-url', config.disqus_url + article_id).text('комментарии');
      $(wrap_header).append(comment_count);
      reset_disqus(article_id, $(marked(item.title.content.trim())).text());
    }
  } else {
    $(wrap_area).append("Статью найти не удалось");
  }
  $('html').addClass('noscroll');
  $('#modal_page_article').scrollTop(0).scrollTop(0).fadeIn(500);
};

gen_li_to_page_status_line = function(content) {
  var item;
  item = document.createElement('li');
  $(item).html(content);
  return item;
};

gen_icon_to_page_status_line = function(type) {
  var item;
  item = document.createElement('li');
  if (type === 'gallery') {
    $(item).attr('class', 'glyphicon glyphicon-camera');
  } else if (type === 'comments') {
    $(item).attr('class', 'glyphicon glyphicon-comment');
  } else if (type === 'file') {
    $(item).attr('class', 'glyphicon glyphicon-file');
  }
  return item;
};

set_article = function(data, file_name, callback) {
  var article_id, count, detail, detail_icon, detail_msg, head, icon, li, link, link_menu, list_element, p, page, picture, status_line, text_head;
  page = document.createElement('div');
  $(page).attr('class', 'page_short');
  list_element = document.createElement('ul');
  $(list_element).attr('class', 'page_status_line__list');
  article_id = data.uid.content.trim();
  status_line = document.createElement('div');
  $(status_line).attr('class', 'page_status_line').append(list_element);
  if (data.title) {
    text_head = $(marked(data.title.content)).text().trim();
    head = document.createElement('h1');
    $(head).attr('class', 'page-header');
    li = document.createElement('li');
    $(li).attr('role', 'presentation');
	
    link_menu = document.createElement('a');	
	
    
	$(link_menu).attr('href', '#' + config.disqus_url_v + '_' + article_id).attr('data-id', file_name).attr('class', 'btn detail').text(text_head);
	
    
	$(link_menu).on('click', function(event) {
      var target;
      event = event || window.event;
	  alert("fdsa");
      target = event.target || event.srcElement;
      event.preventDefault();
      file_name = $(this).attr('data-id');
	  
      get_article(file_name);
    });
	
	
    $(li).append(link_menu);
	
    $('#menu').append(li);
    count = parseInt($('#count').text()) + 1;
    $('#count').text(count);
    link = document.createElement('a');
    $(link).attr('href', '#short_' + config.disqus_url_v + '_' + article_id).attr('name', 'short_' + config.disqus_url_v + '_' + article_id).attr('data-id', file_name).text(text_head);
	
	$(link).on('click', function(event) {
      var target;
      event = event || window.event;
	  
      target = event.target || event.srcElement;
      event.preventDefault();
      file_name = $(this).attr('data-id');
	  
      get_article(file_name);
    });
    $(head).append(link);
    $(page).append(head);
    $(page).append(status_line);
  }
  if (data.time) {
    $(list_element).append(gen_li_to_page_status_line(data.time.content.trim()));
  }
  if (data.picture) {
    picture = $(marked(data.picture.content)).find('img');
    //$(picture).addClass('point');
    icon = document.createElement('span');
	p = document.createElement('p');
    $(p).attr('class', 'pull-left image_fat').append(picture).append(icon);
    /*
	$(icon).attr('class', 'icon_expand_36_white point');
    
    $(p).on('click', function(event) {
      var autoHeight, curHeight;
      curHeight = $(this).height();
      autoHeight = $(this).css('height', 'auto').height();
      $(this).height(curHeight).animate({
        height: autoHeight
      }, 300, function() {
        $(this).find('span').hide();
        $(picture).removeClass('point');
        $(icon).removeClass('point');
      });
    });
	*/
    $(page).append(p);
  }
  if (data.shortdesc) {
	  shortdesc = document.createElement('div');	
	  $(shortdesc).addClass("shortdesc");
	  $(shortdesc).append(marked(data.shortdesc.content));
    $(page).append(shortdesc);
  }
  if (data.gallery) {
    $(list_element).append(gen_icon_to_page_status_line('gallery'));
  }
  if (data.comments && data.comments.value === true) {
    $(list_element).append(gen_icon_to_page_status_line('comments'));
  }
  if (data.file) {
    $(list_element).append(gen_icon_to_page_status_line('file'));
  }
  detail = document.createElement('a');
  detail_msg = document.createElement('div');
  $(detail_msg).attr('class', 'detail__msg').text('подробнее');
  detail_icon = document.createElement('div');
  $(detail_icon).attr('class', 'detail__icon glyphicon glyphicon-chevron-down');
  $(detail).attr('href', '#' + config.disqus_url_v + '_' + article_id).attr('data-id', file_name).attr('class', 'btn detail').append(detail_msg).append(detail_icon);
  $(detail).on('click', function(event) {
    var target;
    event = event || window.event;
    target = event.target || event.srcElement;
    event.preventDefault();
    file_name = $(this).attr('data-id');
    get_article(file_name);
  });
  $(page).append(detail);
  $('#notes_wrap').append(page);
  if (callback) {
    callback();
  }
};

render_article_short = function() {
  var item;
  if (config.cursor === void 0 || config.cursor === "undefined") {
    config.cursor = 0;
  }
  if (config.cursor_need === void 0 || config.cursor === "undefined") {
    config.cursor_need = config.preload;
  }
  item = config.query[config.cursor];
  $.ajax({
    url: '../jsondbase/' + item + '.json',
    cache: false,
    type: 'GET',
    dataType: 'json',
    async: true,
    success: function(data, status, jqhxr) {
      config.cursor += 1;
      if (config.cursor < config.cursor_need && config.cursor < config.query.length) {
        set_article(data, item, render_article_short);
      } else {
        set_article(data, item);
      }
    }
  });
};

init_article = function() {
  var fn, i, j, len, query, query_raw;
  query_raw = $('.setting_article_query').text().trim().split('\n');
  query = config.query = [];
  fn = function(i) {
    query.push(i.trim());
  };
  for (j = 0, len = query_raw.length; j < len; j++) {
    i = query_raw[j];
    fn(i);
  }
  render_article_short();
};

load_more_article = function() {
  var prev_cursor_need;
  prev_cursor_need = config.cursor_need;
  if (config.cursor < config.query.length) {
    config.cursor_need += config.postload;
    if (config.cursor_need < config.query.length) {
      render_article_short();
    } else if (config.cursor_need >= config.query.length) {
      config.cursor_need = prev_cursor_need;
      config.cursor_need += config.query.length - config.cursor;
      render_article_short();
    }
  } else {
    $('#load_more_article').fadeOut(500).text(config.page_overload).fadeIn(500);
  }
};

get_menu = function() {
  var fn, item, items, j, len;
  items = config.menu;
  fn = function(item) {
    $.ajax({
      url: '../jsondbase/' + item.name + '.' + item.type,
      cache: false,
      type: 'GET',
      dataType: 'json',
      async: true,
      success: function(data, status, jqhxr) {
        set_menu(data, item.place);
      }
    });
  };
  for (j = 0, len = items.length; j < len; j++) {
    item = items[j];
    fn(item);
  }
};

randomNumber = function(minimum, maximum) {
  return Math.round(Math.random() * (maximum - minimum) + minimum);
};

urlParser = function() {
  var file_name, reg_1, reg_2, url, url_raw;
  url_raw = location.href;
  url = url_raw.split('#');
  reg_1 = /^short_post/;
  reg_2 = /^post/;
  if (url.length > 1) {
    if (reg_1.test(url[1])) {
      file_name = url[1].split('_')[2];
      get_article(file_name);
    } else if (reg_2.test(url[1])) {
      file_name = url[1].split('_')[1];
      get_article(url[1].split('_')[1]);
    }
  }
};

set_title = function(text) {
  var title;
  title = $('title')[0];
  title.textContent = text.split('').slice(0, 20).join().replace(/,/g, '') + '...';
};

setup_banner = {
  settings_slides: function(cb) {
    var fn, i, j, len, ref;
    ref = config.slide_items;
    fn = function(i) {
      var image, item;
      item = document.createElement('a');
      $(item).attr('class', 'item');
      if(i[1] !== ""){
        $(item).attr("href", i[1]);
        if(i[2] != "") $(item).attr("target", i[2]);
      }
      image = document.createElement('img');
      $(image).attr('class', 'lazyOwl').attr('data-src', 'slides/' + i[0]).attr('src', '').attr('alt', 'slide ' + i[0]);
      $(item).append(image);
      $('#slides').append(item);
    };
    for (j = 0, len = ref.length; j < len; j++) {
      i = ref[j];
      fn(i);
    }
    cb();
  },
  owl_set: function() {
    $('#slides').owlCarousel({
      slideSpeed: 200,
      paginationSpeed: 1000,
      singleItem: true,
      autoPlay: 5000,
      navigation: true,
      pagination: false,
      lazyLoad: true
    });
  },
  owl_banners: function() {
    $('.mini_slide').owlCarousel({
      slideSpeed: 500,
      paginationSpeed: 500,
      singleItem: true,
      autoPlay: false,
      navigation: true,
      pagination: false,
      lazyLoad: true
    });
    $('.slide_partners').owlCarousel({
      autoPlay: 10000,
      items: 4,
      itemsDescktop: [1024, 4],
      itemsDescktopSmall: [1023, 4]
    });
  },
  run: function() {
    this.settings_slides(this.owl_set);
    this.owl_banners();
  }
};


modal_call = {
  init: function() {
    if (ls.attention === void 0 || ls.attention === "undefined") {
      ls.attention = JSON.stringify(false);
    }
  },
  call: function() {
    $('.modal').modal('show');
  },
  timer: function(cb) {
    if (JSON.parse(ls.attention) === false) {
      setTimeout(cb, 3000);
    }
  },
  bind: function() {
    $('button[data-dismiss="modal"]').on('click', function(event) {
      ls.attention = JSON.stringify(true);
    });
  },
  run: function() {
    this.init();
    this.bind();
    this.timer(this.call);
  }
};

run_all_run = function() {
  get_menu();
  $(window).scroll(function() {
    if ($(window).scrollTop() > 680) {
      $('.navbar').addClass('navbar-fixed-top');
      $('.navbar > .container').removeClass('container').addClass('container-fluide');
      $('.scroll_to_up').fadeIn(500);
    }
    if ($(window).scrollTop() < 680) {
      $('.navbar').removeClass('navbar-fixed-top');
      $('.navbar > .container-fluide').removeClass('container-fluide').addClass('container');
      $('.scroll_to_up').fadeOut(250);
    }
  });
  $('.scroll_to_up').on('click', function(event) {
    var target;
    event = event || window.event;
    target = event.target || event.srcElement;
    event.preventDefault();
    $('html, body').animate({
      scrollTop: 0
    }, 500);
  });
  dates_events = new Array();

  dates_events_text = $("#dates_events").text();
  dates_events = dates_events_text.split(',');
  console.dir(dates_events);
  $('#calendar').datepicker({
    language: "ru",

    multidate: true,


  });
  var this_date = new Date();

  setDates(this_date.getFullYear(), this_date.getMonth());


  $('#calendar').on('changeMonth', function(event) {
    console.dir(event);

    setDates(event.date.getFullYear(), event.date.getMonth());
  });


  /*
  $('#calendar').on('changeDate', function(event) {});
   $('#calendar').on('mouseover', function(event) {
    var a, target;
    event = event || window.event;
    target = event.target || event.srcElement;
    if ($(target).attr('class') === 'active day' || $(target).attr('class') === 'active disabled day') {
      a = document.createElement('a');
      $(a).attr('href', '#').text('ссылка на статью');
      $('#calendar_popup_area').empty().append('<p>Событие ' + $(target).text() + ' !</p>' + '<p>Далее идет какое-то краткое описание события и </p>').append(a).append('<h4>' + $(target).text() + '</h4>');
      $('#calendar_popup_area').fadeIn(50);
    }
  });
  */
  $('#calendar').on('click', function(event) {
    var target, date;

    event = event || window.event;
    target = event.target || event.srcElement;
    if ($(target).attr('class') === 'active day' || $(target).attr('class') === 'active disabled day')
    {

      var url = "/index.php?action=events&module=articles&date="+$(target).attr('data-date');

      location.href = url;
    }
    //event = event || window.event;
    //target = event.target || event.srcElement;
    //$(target).fadeOut(300);


    //location.href = url;
  });
  /*
  $('[data-action="close"]').on('click', function(event) {
    var object, target;
    event = event || window.event;
    target = event.target || event.srcElement;
    object = $(this).attr('data-object');
    $('html').removeClass('noscroll');
    $('#' + object).fadeOut(500);
    history.back();
  });





  */
  //modal_call.run();
  setup_banner.run();
  $('.banners').attr('style', 'background: url("banners/banner_' + randomNumber(1, 39) + '.jpg")');
  $('#load_more_article').on('click', function(event) {
    var target;
    event = event || window.event;
    target = event.target || event.srcElement;
    event.preventDefault();
    return load_more_article();
  });
  $('.jumbotron img').attr('src', '../img/head_' + randomNumber(1, 19) + '.png');
  setTimeout(init_article, 1000);
  setTimeout(urlParser, 1500);
};

$(function() {
  config = {};
  set_config(run_all_run);
  $('#special_version_thumbler').on('click', function(event) {
    event = event || window.event;
    event.preventDefault();
  });
});


function setDates(year, month)
{
  var url = "/index.php?&action=events_dates&module=articles&year="+year+"&month="+month;


  $.ajax
  (
      {
        'url':url,
        cache: false,
        type: 'GET',
        dataType: 'json',
        async: true,
        success: function(data, status, jqhxr) {


          if(typeof data != 'object') return null;

          $('#calendar').datepicker('setDates', data);
        }
      }
  );
}