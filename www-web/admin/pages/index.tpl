<!DOCTYPE html>
{% include '/partials/metas.tpl' %}
<html lang="en-EN">
    <body>
      {% if logged == true %}
        {% if args.category is defined %}
          {% include 'home.tpl' %}
        {% else %}
          {% include 'dashboard.tpl' %}
        {% endif %}
      {% else %}
        {% include 'login.tpl' %}
      {% endif %}
    </body>
    {% include '/partials/scripts.tpl' %}
<html>
