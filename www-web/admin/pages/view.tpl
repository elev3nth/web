{% if content.columns is not empty %}
  {% include '/partials/breadcrumbs.tpl' %}
  {% include '/partials/tabs.tpl' %}
  {% for ckey, citem in content.columns %}
    <div class="row-item pt-2 border bg-gray-100
    tab-{{ citem.tab is defined ? citem.tab :
    content.title.singular|lower }} hidden">
      <div class="
        text-right align-middle py-[0.4em] my-1
        lg:flex-none lg:w-[100px]
      ">
        {{ citem.name|title }}
      </div>
      <div class="text-left mx-3 my-1 py-[0.4em] lg:flex-1">
        <b>
          {% include [
            '/forms/'~citem.type~'.tpl',
            '/forms/text.tpl'
          ] %}
        </b>
      </div>
    </div>
  {% endfor %}
  {% if content.table.srt|default(false) == true %}
  <div class="row-item pt-2 border bg-gray-100
  tab-{{ locale.backend.tabs.sorting|lower }} hidden">
    <div class="text-left mx-3 my-1 lg:flex-1">
    Sorting
    </div>
  </div>
  {% endif %}
  <div class="row-item pt-2 border bg-gray-100
  tab-{{ locale.backend.tabs.auditing|lower }} hidden">
    <div class="text-left mx-3 my-1 lg:flex-1">
      Auditing
    </div>
  </div>
  <hr />
  {% set hidebc = true %}
  {% include '/partials/breadcrumbs.tpl' %}
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
