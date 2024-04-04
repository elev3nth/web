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
  bg-white breadcrumbs py-4
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
    {% if args.page != list and args.page != create %}
      {% set fieldtitle  = '' %}
      {% for bckey, bcitem in content.columns %}
        {% if bcitem.title|default(false) == true %}
        {% set fieldtitle = bcitem.flds %}
        {% endif %}
      {% endfor %}
      {% if fieldtitle is not empty %}
        <i class="fa-solid fa-slash fa-xs fa-rotate-270 mx-1"></i>
        <span class="font-bold text-[1.1em]">
          {{ content.data[fieldtitle] }}
        </span>
      {% endif %}
    {% endif %}
  {% endif %}
  {% if hidebc|default(false) != true and args.page == list %}
    {% if content.paging.total is defined %}
      <i class="fa-solid fa-slash fa-xs fa-rotate-270 mx-1"></i>
      <span class="font-bold text-[1.1em]">
        {{ content.paging.total }} Record(s) in Page {{ content.paging.min }}
        of {{ content.paging.max }}
      </span>
    {% endif %}
  {% endif %}
  {% if args.page != list and args.page != edit and args.page != delete and
  args.page != create %}
  <a href="{{ host~'/'~admin~'/'~links|join('/')~'/'~list }}"
  class="w-[7em] text-center font-bold
    block float-right mx-[0.3em] p-1 px-2 mt-[-0.3em] border rounded-md
    text-slate-400 bg-slate-100 hover:bg-slate-200 hover:text-slate-500
  " title="{{ buttons.back|title }}">
    <i class="fa-solid fa-angles-left"></i> {{ buttons.back|upper }}
  </a>
  {% endif %}
  {% if args.page == edit or args.page == create %}
  <a href="{{ host~'/'~admin~'/'~links|join('/')~'/'~list }}"
  class="w-[7em] text-center font-bold
    block float-right mx-[0.3em] p-1 px-2 mt-[-0.3em] border rounded-md
    text-slate-400 bg-red-100 hover:bg-red-200 hover:text-slate-700
  " title="{{ buttons.cancel|title }}">
    <i class="fa-solid fa-xmark"></i> {{ buttons.cancel|upper }}
  </a>
  <button type="submit" id="saveRecord" name="saveRecord"
  class="w-[7em] text-center font-bold
    block float-right mx-[0.3em] p-1 px-2 mt-[-0.3em] border rounded-md
    text-slate-400 bg-green-200 hover:bg-green-400 hover:text-slate-700
  " title="{{ buttons.save|title }}">
    <i class="fa-solid fa-floppy-disk"></i> {{ buttons.save|upper }}
  </button>
  {% endif %}
  <a href="{{ host~'/'~admin~'/'~links|join('/')~'/'~create }}"
  class="w-[7em] text-center font-bold
    block float-right mx-[0.3em] p-1 px-2 mt-[-0.3em] border rounded-md
    text-slate-400 bg-slate-100 hover:bg-slate-200 hover:text-slate-500
  " title="{{ create|title }}">
    <i class="fa-solid fa-plus"></i> {{ buttons.create|upper }}
  </a>
</div>
