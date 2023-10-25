{% macro categories(param) %}
  {% for sckey, scitem in param.sctg %}
    {% if scitem.slug in param.args.category %}
      {% if param.hdbc|default(false) != true %}
        <i class="fa-solid fa-slash fa-xs fa-rotate-270 mx-1"></i>
        <span class="font-bold text-[1.1em]">{{ scitem.name }}</span>
      {% else %}
        &nbsp;
      {% endif %}
    {% endif %}
    {% if scitem.sctg is not empty %}
      {% set paramx = {
        'sctg' : scitem.sctg,
        'args' : args,
        'hdbc' : hdbc
      } %}
      {{ _self.categories(paramx) }}
    {% endif %}
  {% endfor %}
{% endmacro categories %}
<div class="
  w-full p-3 rounded-tr-[0.3em] rounded-tl-[0.3em]
  bg-white breadcrumbs py-2
">
  {% if hidebc|default(false) != true %}
  <i class="fa-solid fa-caret-right fa-lg mr-2"></i>
  {% endif %}
  {% for nvbkey, nvbitem in navbar %}
    {% if nvbitem.slug in args.category %}
      {% if hidebc|default(false) != true %}
        <span class="font-bold text-[1.1em]">{{ nvbitem.name }}</span>
      {% else %}
        &nbsp;
      {% endif %}
    {% endif %}
    {% if nvbitem.sctg is not empty %}
      {% set param = {
        'sctg' : nvbitem.sctg,
        'args' : args,
        'hdbc' : hidebc
      } %}
      {{ _self.categories(param) }}
    {% endif %}
  {% endfor %}
  {% for sbrkey, sbritem in sidebar %}
    {% if sbritem.slug in links %}
      {% if hidebc|default(false) != true %}
        <i class="fa-solid fa-slash fa-xs fa-rotate-270 mx-1"></i>
        <span class="font-bold text-[1.1em]">{{ sbritem.name }}</span>
      {% else %}
        &nbsp;
      {% endif %}
    {% endif %}
  {% endfor %}
  {% if hidebc|default(false) != true %}
    {% if args.page is defined %}
      <i class="fa-solid fa-slash fa-xs fa-rotate-270 mx-1"></i>
      <span class="font-bold text-[1.1em]">{{ args.page|title }}</span>
    {% endif %}
  {% endif %}
  {% if hidebc|default(false) != true %}
    {% if content.paging.total is defined %}
      <i class="fa-solid fa-slash fa-xs fa-rotate-270 mx-1"></i>
      <span class="font-bold text-[1.1em]">
        {{ content.paging.total }} Record(s) in Page {{ content.paging.min }}
        of {{ content.paging.max }}
      </span>
    {% endif %}
  {% endif %}
  {% if args.page != list %}
  <a href="{{ host~'/'~admin~'/'~links|join('/')~'/'~list }}"
  class="
    block float-right mx-1 p-1 px-2 mt-[-0.3em] border rounded-md
    text-slate-400 bg-slate-100 hover:bg-slate-200 hover:text-slate-500
  " title="Back">
    <i class="fa-solid fa-angles-left font-bold"></i>
  </a>
  {% endif %}
  <a href="{{ host~'/'~admin~'/'~links|join('/')~'/'~create }}"
  class="
    block float-right mx-1 p-1 px-2 mt-[-0.3em] border rounded-md
    text-slate-400 bg-slate-100 hover:bg-slate-200 hover:text-slate-500
  " title="{{ create|title }}">
    <i class="fa-solid fa-plus font-bold"></i>
  </a>
</div>
