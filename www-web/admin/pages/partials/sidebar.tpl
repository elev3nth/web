<div class="w-full h-full sidebar">
  <a href="{{ host~'/'~admin }}">
    <div class="
      bg-main-logo bg-center bg-no-repeat bg-contain
      m-auto py-[2em] w-[4em] h-[4em]
    "></div>
  </a>
  <div class="
    sidelinks w-full border-t-[0.1em] border-neutral-300
    border-solid sticky top-0
  ">
    {% for nkey, nitem in navbar %}
      {% if nitem.slug == args.category or
      nitem.default|default(false) == true %}
        {% for skey, sitem in sidebar %}
          {% if (nitem.ukey == sitem.ckey) %}
          <a href="{{ host~'/'~admin~'/'~nitem.slug~'/'~sitem.slug~'/'~list }}" class="
            sidelink w-full p-2 border-b-[0.1em] border-neutral-300 border-solid
            {{ sitem.slug == args.application ? 'active' : '' }}
          ">
            {{ sitem.name }}
          </a>
          {% endif %}
        {% endfor %}
      {% endif %}
    {% endfor %}
  </div>
</div>
