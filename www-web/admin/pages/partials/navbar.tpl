<div class="navbar w-full h-[4em] p-[1.2em] px-[3em] text-right">
  {% set navctg = '' %}
  {% for ctg in args.category %}
    {% for nkey, nitem in navbar %}
      {% if nitem.slug == ctg %}
        {% set navctg = nitem.slug %}
      {% endif %}
    {% endfor %}
  {% endfor %}
  {% for nkey, nitem in navbar %}
  <a href="{{ host~'/'~admin~'/'~nitem.slug }}" class="
    navlink text-xl
    {% if navctg != '' %}
      {{ nitem.slug == navctg ? 'active' : '' }}
    {% else %}
      {{ nitem.default|default(false) == true ? 'active' : '' }}
    {% endif %}
  ">{{ nitem.name }}</a>
  {% endfor %}
  <a href="javascript:void(0);" class="
    border border-slate-400 border-solid rounded-[50%]
    p-[0.4em] ml-[1em] bg-white
  ">
    <i class="fas fa-user fa-xl text-slate-400"></i>
  </a>
</div>
