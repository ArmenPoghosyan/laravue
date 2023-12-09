<?php

return array (
  'between' => 
  array (
    'array' => 'Поле :attribute должно содержать от :min до :max элементов.',
    'file' => 'Поле :attribute должно быть от :min до :max килобайт.',
    'numeric' => 'Поле :attribute должно быть от :min до :max.',
    'string' => 'Поле :attribute должно содержать от :min до :max символов.',
  ),
  'gt' => 
  array (
    'array' => 'Поле :attribute должно содержать более :value элементов.',
    'file' => 'Поле :attribute должно быть больше :value килобайт.',
    'numeric' => 'Поле :attribute должно быть больше :value.',
    'string' => 'Поле :attribute должно содержать более :value символов.',
  ),
  'gte' => 
  array (
    'array' => 'Поле :attribute должно содержать :value элементов или больше.',
    'file' => 'Поле :attribute должно быть больше или равно :value килобайт.',
    'numeric' => 'Поле :attribute должно быть больше или равно :value.',
    'string' => 'Поле :attribute должно содержать больше или равно :value символов.',
  ),
  'lt' => 
  array (
    'array' => 'Поле :attribute должно содержать менее :value элементов.',
    'file' => 'Поле :attribute должно быть меньше :value килобайт.',
    'numeric' => 'Поле :attribute должно быть меньше :value.',
    'string' => 'Поле :attribute должно содержать менее :value символов.',
  ),
  'lte' => 
  array (
    'array' => 'Поле :attribute не должно содержать более :value элементов.',
    'file' => 'Поле :attribute должно быть меньше или равно :value килобайт.',
    'numeric' => 'Поле :attribute должно быть меньше или равно :value.',
    'string' => 'Поле :attribute должно содержать меньше или равно :value символов.',
  ),
  'max' => 
  array (
    'array' => 'Поле :attribute не должно содержать более :max элементов.',
    'file' => 'Поле :attribute не должно быть больше :max килобайт.',
    'numeric' => 'Поле :attribute не должно быть больше :max.',
    'string' => 'Поле :attribute не должно содержать более :max символов.',
  ),
  'min' => 
  array (
    'array' => 'Поле :attribute должно содержать как минимум :min элементов.',
    'file' => 'Поле :attribute должно быть как минимум :min килобайт.',
    'numeric' => 'Поле :attribute должно быть как минимум :min.',
    'string' => 'Поле :attribute должно содержать как минимум :min символов.',
  ),
  'password' => 
  array (
    'letters' => 'Поле :attribute должно содержать как минимум одну букву.',
    'mixed' => 'Поле :attribute должно содержать как минимум одну заглавную букву и одну строчную букву.',
    'numbers' => 'Поле :attribute должно содержать как минимум одну цифру.',
    'symbols' => 'Поле :attribute должно содержать как минимум один символ.',
    'uncompromised' => 'Указанное поле :attribute появилось в утечке данных. Пожалуйста, выберите другое значение для :attribute.',
    'invalid' => 'Недопустимый пароль',
    'incorrect' => 'Неправильный пароль.',
  ),
  'size' => 
  array (
    'array' => 'Поле :attribute должно содержать :size элементов.',
    'file' => 'Поле :attribute должно быть :size килобайт.',
    'numeric' => 'Поле :attribute должно быть :size.',
    'string' => 'Поле :attribute должно содержать :size символов.',
  ),
  'accepted' => 'Поле :attribute должно быть принято.',
  'accepted_if' => 'Поле :attribute должно быть принято, когда :other равно :value.',
  'active_url' => 'Поле :attribute не является действительным URL.',
  'after' => 'Поле :attribute должно быть датой после :date.',
  'after_or_equal' => 'Поле :attribute должно быть датой после или равной :date.',
  'alpha' => 'Поле :attribute должно содержать только буквы.',
  'alpha_dash' => 'Поле :attribute должно содержать только буквы, цифры, дефисы и подчеркивания.',
  'alpha_num' => 'Поле :attribute должно содержать только буквы и цифры.',
  'array' => 'Поле :attribute должно быть массивом.',
  'before' => 'Поле :attribute должно быть датой до :date.',
  'before_or_equal' => 'Поле :attribute должно быть датой до или равной :date.',
  'boolean' => 'Поле :attribute должно быть true или false.',
  'confirmed' => 'Подтверждение поля :attribute не совпадает.',
  'current_password' => 'Неверный пароль.',
  'date' => 'Поле :attribute не является действительной датой.',
  'date_equals' => 'Поле :attribute должно быть датой, равной :date.',
  'date_format' => 'Поле :attribute не соответствует формату :format.',
  'declined' => 'Поле :attribute должно быть отклонено.',
  'declined_if' => 'Поле :attribute должно быть отклонено, когда :other равно :value.',
  'different' => 'Поле :attribute и :other должны отличаться.',
  'digits' => 'Поле :attribute должно содержать :digits цифр.',
  'digits_between' => 'Поле :attribute должно содержать от :min до :max цифр.',
  'dimensions' => 'Загруженный файл имеет недопустимые размеры изображения.',
  'distinct' => 'Поле :attribute имеет повторяющееся значение.',
  'email' => 'Поле :attribute должно быть действительным адресом электронной почты.',
  'ends_with' => 'Поле :attribute должно заканчиваться одним из следующих: :values.',
  'enum' => 'Выбранное значение :attribute недопустимо.',
  'exists' => 'Выбранное значение :attribute недопустимо.',
  'file' => 'Поле :attribute должно быть файлом.',
  'filled' => 'Поле :attribute должно иметь значение.',
  'image' => 'Поле :attribute должно быть изображением.',
  'in' => 'Выбранное значение :attribute недопустимо.',
  'in_array' => 'Поле :attribute не существует в :other.',
  'integer' => 'Поле :attribute должно быть целым числом.',
  'ip' => 'Поле :attribute должно быть действительным IP-адресом.',
  'ipv4' => 'Поле :attribute должно быть действительным IPv4-адресом.',
  'ipv6' => 'Поле :attribute должно быть действительным IPv6-адресом.',
  'json' => 'Поле :attribute должно быть действительной JSON-строкой.',
  'mac_address' => 'Поле :attribute должно быть действительным MAC-адресом.',
  'mimes' => 'Поле :attribute должно быть файлом типа: :values.',
  'mimetypes' => 'Поле :attribute должно быть файлом типа: :values.',
  'multiple_of' => 'Поле :attribute должно быть кратным :value.',
  'not_in' => 'Выбранное значение :attribute недопустимо.',
  'not_regex' => 'Формат поля :attribute недопустим.',
  'numeric' => 'Поле :attribute должно быть числом.',
  'present' => 'Поле :attribute должно быть присутствующим.',
  'prohibited' => 'Поле :attribute запрещено.',
  'prohibited_if' => 'Поле :attribute запрещено, когда :other равно :value.',
  'prohibited_unless' => 'Поле :attribute запрещено, если :other не принадлежит к :values.',
  'prohibits' => 'Поле :attribute запрещает наличие :other.',
  'regex' => 'Формат поля :attribute недопустим.',
  'required' => 'Поле :attribute обязательно для заполнения.',
  'required_array_keys' => 'Поле :attribute должно содержать записи для: :values.',
  'required_if' => 'Поле :attribute обязательно, когда :other равно :value.',
  'required_unless' => 'Поле :attribute обязательно, если :other не принадлежит к :values.',
  'required_with' => 'Поле :attribute обязательно, когда :values присутствует.',
  'required_with_all' => 'Поле :attribute обязательно, когда присутствуют все значения: :values.',
  'required_without' => 'Поле :attribute обязательно, когда :values отсутствует.',
  'required_without_all' => 'Поле :attribute обязательно, когда отсутствуют все значения: :values.',
  'same' => 'Поле :attribute и :other должны совпадать.',
  'starts_with' => 'Поле :attribute должно начинаться с одного из следующих: :values.',
  'doesnt_start_with' => 'Поле :attribute не должно начинаться с одного из следующих: :values.',
  'string' => 'Поле :attribute должно быть строкой.',
  'timezone' => 'Поле :attribute должно быть действительным часовым поясом.',
  'unique' => 'Поле :attribute уже занято.',
  'uploaded' => 'Поле :attribute не удалось загрузить.',
  'url' => 'Поле :attribute должно быть действительным URL.',
  'uuid' => 'Поле :attribute должно быть действительным UUID.',
  'attributes' => 
  array (
    'password_confirm' => 'Подтверждение пароля',
  ),
  'youtube_url' => 'Поле :attribute не является Youtube ссылкой',
);
