{% if content.columns is not empty and args.uuid is defined %}
  <form method="post" action="
    {{ host~'/'~admin~'/'~links|join('/')~'/'~edit~'/'~args.uuid }}
  ">
    {% include '/partials/breadcrumbs.tpl' %}
    {% if crud_response is defined %}
      {% set errfields = [] %}
      {% if crud_response.status == 'success' or
      crud_response.status == 'created' %}
        {% set colorscheme = 'bg-green-200 text-green-600' %}
      {% elseif crud_response.status == 'validate' %}
        {% set colorscheme = 'bg-red-200 text-red-600' %}
        {% for vkey, vitem in crud_response.errors %}
          {% set errfields = errfields|merge([vitem.field]) %}
        {% endfor %}
      {% else %}
        {% set colorscheme = 'bg-slate-200 text-slate-600' %}
      {% endif %}
      <div class="w-full text-center py-2 lg:py-4 text-[1em] lg:text-[1.5em]
      font-bold {{ colorscheme }}">
        {{ crud_response.message }}
      </div>
    {% endif %}
    {% include '/partials/tabs.tpl' %}
    {% for ckey, citem in content.columns %}
      <div class="lg:flex pt-2 border bg-gray-100">
        <div class="
          text-right align-middle py-[0.4em] my-1
          lg:flex-none lg:w-[100px] relative
        ">
          {% if citem.auths.required or citem.auths.unique %}
          <div class="position absolute top-[-0.4em] left-1 text-md
          text-slate-400">
            {% if citem.auths.required %}
            *
            {% endif %}
            {% if citem.auths.unique %}
            *
            {% endif %}
          </div>
          {% endif %}
          {{ citem.name|title }}
        </div>
        <div class="text-left mx-3 my-1 lg:flex-1">
          {% include [
            '/forms/'~citem.type~'.tpl',
            '/forms/text.tpl'
          ] %}
        </div>
      </div>
    {% endfor %}
    <hr />
    {% set hidebc = true %}
    {% include '/partials/breadcrumbs.tpl' %}
    <input type="hidden" id="userCsrf" name="userCsrf"
    value="{{ csrf }}" />
  </form>
{% else %}
  <h2 class="
    list-error w-full m-4 text-center text-[3em] font-bold
    text-slate-400 lg:text-[5em]
  ">
    <i class="fa-solid fa-screwdriver-wrench m-0 my-3 mt-[0.3em] fa-2x"></i>
    <br />
    {% if content.title.plural is defined %}
      {{ content.title.plural ~ ' ' ~
         locale.backend.content.errors.not_configured }}
    {% else %}
      {{ locale.backend.content.errors.app_not_configured }}
    {% endif %}
  <h2>
{% endif %}
