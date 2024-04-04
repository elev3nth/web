{% if content.columns is not empty %}
  <form method="post" action="
    {{ host~'/'~admin~'/'~links|join('/')~'/'~delete~'/'~args.uuid }}
  ">
    {% include '/partials/breadcrumbs.tpl' %}
    <br />
    {% for ckey, citem in content.columns %}
      {% if content.data[citem.flds] is defined and
         citem.title|default(false) == true and 'D' in citem.crud %}
      <div class="delete-msg w-full m-4 text-center text-[3em] font-bold
        text-red-400 lg:text-[4em]
      ">
        <i class="fa-solid fa-circle-exclamation m-0 my-3 mt-[0.3em] fa-2x"></i>
        <h1>Are you sure to delete {{ content.data[citem.flds] }}?</h1>
        <div class="text-center w-full my-[0.4em] flex justify-center">
          <a href="{{ host~'/'~admin~'/'~links|join('/')~'/'~list }}"
          class="w-[8em] text-center text-xl font-bold tracking-normal
            block mx-[0.8em] p-2 px-2 border rounded-md
            text-slate-400 bg-slate-100 hover:bg-slate-200 hover:text-slate-700
          " title="{{ buttons.cancel }}">
            <i class="fa-solid fa-xmark"></i> {{ buttons.cancel|upper }}
          </a>
          <button type="submit" id="deleteRecord" name="deleteRecord"
          class="w-[8em] text-center text-xl font-bold
            block mx-[0.8em] p-2 px-2 border rounded-md
            text-slate-400 bg-red-100 hover:bg-red-200 hover:text-slate-700
          " title="{{ buttons.continue }}">
            <i class="fa-solid fa-floppy-disk"></i> {{ buttons.continue|upper }}
          </button>
        </div>
      </div>
      {% endif %}
    {% endfor %}
    <br />
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
