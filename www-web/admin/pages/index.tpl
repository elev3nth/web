<!DOCTYPE html>
{% include '/partials/metas.tpl' %}
<html lang="en-EN">
    <body>
      {% if logged == true %}
        {% include 'home.tpl' %}
      {% else %}
        {% include 'login.tpl' %}
      {% endif %}
    </body>
    {% include '/partials/scripts.tpl' %}
<html>
