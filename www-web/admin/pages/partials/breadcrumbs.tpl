<div class="
  w-full p-3 rounded-tr-[0.3em] rounded-tl-[0.3em]
  bg-white breadcrumbs py-2
">
  {% if hidebc|default(false) != true %}
  <i class="fa-solid fa-caret-right fa-lg mr-2"></i>
  {% endif %}
  {% set bcnvslug = '' %}
  {% set bcsbslug = '' %}
  {% for nvbkey, nvbitem in navbar %}
    {% if nvbitem.slug in links %}
      {% set bcnvslug = nvbitem.slug %}
      {% if hidebc|default(false) != true %}
        <span class="font-bold text-[1.1em]">{{ nvbitem.name }}</span>
      {% else %}
        &nbsp;
      {% endif %}
    {% endif %}
  {% endfor %}
  {% for sbrkey, sbritem in sidebar %}
    {% if sbritem.slug in links %}
      {% set bcsbslug = sbritem.slug %}
      {% if hidebc|default(false) != true %}
        <i class="fa-solid fa-slash fa-xs fa-rotate-270 mx-1"></i>
        <span class="font-bold text-[1.1em]">{{ sbritem.name }}</span>
      {% else %}
        &nbsp;
      {% endif %}
    {% endif %}
  {% endfor %}
  {% if args.page != list %}
  <a href="{{ host~'/'~admin~'/'~bcnvslug~'/'~bcsbslug~'/'~list }}"
  class="
    block float-right mx-1 p-1 px-2 mt-[-0.3em] border rounded-md
    text-slate-400 bg-slate-100 hover:bg-slate-200 hover:text-slate-500
  " title="Back">
    <i class="fa-solid fa-angles-left font-bold"></i>
  </a>
  {% endif %}
  <a href="{{ host~'/'~admin~'/'~bcnvslug~'/'~bcsbslug~'/'~create }}"
  class="
    block float-right mx-1 p-1 px-2 mt-[-0.3em] border rounded-md
    text-slate-400 bg-slate-100 hover:bg-slate-200 hover:text-slate-500
  " title="{{ create|title }}">
    <i class="fa-solid fa-plus font-bold"></i>
  </a>
</div>
