var get_number_command, list_image_render, msg_render, s, socket;

socket = s = {
  messages: {
    error: {
      host: "ошибка host сервера",
      server: "возникла ошибка в работе клиент-сервера",
      dir: "вы не указали название папки",
      ftp: {
        username: "вы не ввели имя пользователя ftp",
        password: "вы не ввели пароль пользователя ftp",
        host: "вы не ввели адрес сервера ftp",
        send: "во время ввода информации для ftp соединения были допущены ошибки"
      }
    },
    access: {
      onopen: "соединение с сервером установленно",
      onclose: "соединение с сервером разорвано"
    }
  },
  host: '',
  port: ':40077',
  type: 'ws://'
};

get_number_command = function() {
  var number;
  if (socket.number_command) {
    socket.number_command += 1;
    number = socket.number_command;
  } else {
    socket.number_command = 1;
    number = socket.number_command;
  }
  return number;
};

msg_render = function(type, text) {
  var msg;
  msg = document.createElement('div');
  $(msg).attr('class', 'alert alert-' + type).text(get_number_command() + ": " + text).hide();
  $('#messages_line').prepend(msg);
  $(msg).fadeIn(500);
};

$('[data-command]').on('click', function(event) {
  var command, control, dir, host, password, target, username;
  event = event || window.event;
  target = event.target || event.srcElement;
  command = $(target).attr('data-command');
  if (command === 'images_zip') {
    dir = $('#images_zip_data').val();
    if (dir !== "") {
      $('.list_image_element').empty();
      s.image_path = dir;
      s.connection.send('images_zip ' + dir);
    } else {
      msg_render('warning', s.messages.error.dir);
    }
  } else if (command === 'images_list') {
    dir = $('#images_list_data').val();
    if (dir !== "") {
      $('.list_dir_image_element').empty();
      s.image_path = dir;
      s.ws.send('images_dir_list ' + dir);
    } else {
      msg_render('warning', s.messages.error.dir);
    }
  } else if (command === 'upgrade_pages') {
    s.ws.send('upgrade_pages');
  } else if (command === 'deploy_changed') {
    control = true;
    username = $('input[name="ftp[username]"]').val();
    password = $('input[name="ftp[password]"]').val();
    host = $('input[name="ftp[host]"]').val();
    if (username === "") {
      msg_render('warning', s.messages.error.ftp.username);
      control = false;
    }
    if (password === "") {
      msg_render('warning', s.messages.error.ftp.password);
      control = false;
    }
    if (host === "") {
      msg_render('warning', s.messages.error.ftp.host);
      control = false;
    }
    if (control) {
      s.ws.send('deploy_changed:' + username + ':' + password + ':' + host);
    } else {
      msg_render('warning', s.messages.error.ftp.send);
    }
  } else {
    s.connection.send('проверка связи');
  }
});

list_image_render = function(place, name) {
  var txt;
  txt = '![image]' + '(../images/' + s.image_path + '/' + name + ')' + '\n';
  $(place).append(txt);
};

socket.init = function() {
  s.host = location.hostname;
  if (s.host && s.port && s.type) {
    socket.connection = socket.ws = new WebSocket(s.type + s.host + s.port);
    socket.connection.onopen = function() {
      msg_render('success', s.messages.access.onopen);
    };
    socket.connection.onerror = function(error) {
      msg_render('danger', s.messages.error.server);
    };
    socket.connection.onclose = function() {
      msg_render('warning', s.messages.access.onclose);
    };
    socket.connection.onmessage = function(message) {
      if (/warning/.test(message.data)) {
        msg_render('warning', message.data);
      } else if (/error/.test(message.data)) {
        msg_render('danger', message.data);
      } else if (/success/.test(message.data)) {
        msg_render('success', message.data);
      } else if (/image_zip/.test(message.data)) {
        list_image_render('.list_image_element', message.data.split(' ')[1]);
      } else if (/image_list/.test(message.data)) {
        list_image_render('.list_dir_image_element', message.data.split(' ')[1]);
      } else {
        msg_render('info', message.data);
      }
    };
  } else {
    msg_render('danger', s.messages.error.server);
  }
};

$(function() {
  socket.init();
});
