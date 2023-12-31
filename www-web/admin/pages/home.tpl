<div class="w-full flex flex-wrap">
  <div class="grow-0 basis-60 h-screen">
    {% include '/partials/sidebar.tpl' %}
  </div>
  <div class="grow h-screen">
    {% include '/partials/navbar.tpl' %}
    <div class="
      m-[0.5em] min-h-max border border-slate-100 border-solid
      rounded-[0.5em]
      bg-white/30
    ">
      {% if args.application is defined %}
        {% if args.page is defined %}
          {% include '/'~args.page~'.tpl' %}
        {% else %}
          {% include '/list.tpl' %}
        {% endif %}
      {% else %}
        {% include '/categories.tpl' %}
      {% endif %}
    </div>
  </div>
</div>
