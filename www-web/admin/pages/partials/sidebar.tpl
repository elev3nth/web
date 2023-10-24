
{% macro iterate(param) %}
  {% for skey, scitem in param.nbar.sctg %}
    {% if scitem.sctg is not empty %}
      {% set paramx = {
        'nbar'  : scitem,
        'nctg'  : param.nctg,
        'sbar'  : param.sbar,
        'args'  : param.args,
        'host'  : param.host,
        'admin' : param.admin,
        'list'  : param.list
      } %}
      {{ _self.iterate(paramx) }}
    {% else %}
      {% if (scitem.prnt == param.nctg) or
        (param.nctg is empty and scitem.default|default(false) == true) %}
        <a href="{{
          param.host~'/'~param.admin~'/'~param.nbar.slug~'/'~scitem.slug
        }}"
          class="
          sidelink-ctg w-full p-2 border-b-[0.1em] border-neutral-300
          border-solid
          {{ scitem.prnt == param.nctg ? 'active' : '' }}
        ">
          {{ scitem.name }}
        </a>
        {% if scitem.prnt == param.nbar.ukey %}
          {% for skey, sitem in param.sbar %}
            {% if (scitem.ukey == sitem.ckey) %}
              <a href="{{ param.host~'/'~param.admin~'/'~param.nbar.slug~'/'~
                scitem.slug~'/'~sitem.slug~'/'~param.list }}"
                class="
                sidelink w-full p-2 border-b-[0.1em] border-neutral-300
                border-solid
                {{ sitem.slug == param.args.application ? 'active' : '' }}
              ">
                {{ sitem.name }}
              </a>
            {% endif %}
          {% endfor %}
        {% endif %}
      {% endif %}
    {% endif %}
  {% endfor %}
{% endmacro iterate %}

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
    {% set navctg = '' %}
    {% for ctg in args.category %}
      {% for nitem in navbar %}
        {% if nitem.slug == ctg %}
          {% set navctg = nitem.ukey %}
        {% endif %}
      {% endfor %}
    {% endfor %}
    {% for nkey, nitem in navbar %}
      {% if nitem.sctg is not empty %}
        {% set param = {
          'nbar'  : nitem,
          'nctg'  : navctg,
          'sbar'  : sidebar,
          'args'  : args,
          'host'  : host,
          'admin' : admin,
          'list'  : list
        } %}
        {{ _self.iterate(param) }}
        <!--
        {% for snkey, snitem in nitem.sctg %}
          {% if nitem.slug == args.category or
          nitem.default|default(false) == true %}
          <a href="{{ host~'/'~admin~'/'~nitem.slug~'/'~sitem.slug~'/'~list }}" class="
            sidelink-ctg w-full p-2 border-b-[0.1em] border-neutral-300 border-solid
            {{ sitem.slug == args.application ? 'active' : '' }}
          ">
            {{ snitem.name }}
          </a>
          {% if snitem.prnt == nitem.ukey %}
            {% for skey, sitem in sidebar %}
              {% if (snitem.ukey == sitem.ckey) %}
          <a href="{{ host~'/'~admin~'/'~nitem.slug~'/'~sitem.slug~'/'~list }}" class="
            sidelink w-full p-2 border-b-[0.1em] border-neutral-300 border-solid
            {{ sitem.slug == args.application ? 'active' : '' }}
          ">
            {{ sitem.name }}
          </a>
              {% endif %}
            {% endfor %}
          {% endif %}
          {% endif %}
        {% endfor %}
        -->
      {% else %}
        {% if (nitem.ukey == navctg) or
          (navctg is empty and nitem.default|default(false) == true) %}
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
      {% endif %}
    {% endfor %}
  </div>
</div>
