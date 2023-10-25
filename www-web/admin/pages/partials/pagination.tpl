<div class="w-full bg-white text-center py-[1em]">
  {% if content.paging.total != 0 %}
      {% if ((content.paging.min <= content.paging.max) or
        (content.paging.min <= args.pagenum)) %}
          {{ content.paging.prev }}
          {% if content.paging.prev != null or args.pagenum > 1 %}
            <div class="page text-sm lg:text-xs">
              <a href="{{ host~'/'~admin~'/'~links|join('/')~'/' }}">
                <i class="fa-solid fa-chevron-left"></i>
              </a>
            </div>
          {% else %}
            <div class="page text-sm lg:text-xs">
              <a href="javascript:void(0);" class="disabled">
                <i class="fa-solid fa-chevron-left"></i>
              </a>
            </div>
          {% endif %}
      {% endif %}
      {% if content.paging.pages is not empty %}
          {% for paging in content.paging.pages %}
          <div class="page text-sm lg:text-xs">
              {% if paging.page == args.pagenum %}
                <span class="active">{{ paging.page }}</span>
              {% else %}
                <a href="{{ host~'/'~admin~'/'~links|join('/')~'/' }}">
                  {{ paging.page }}
                </a>
              {% endif %}
          </div>
          {% endfor %}
      {% endif %}
      {% if args.pagenum < content.paging.max %}
          <div class="page text-sm lg:text-xs">
            <a href="{{ host~'/'~admin~'/'~links|join('/')~'/' }}">
              <i class="fa-solid fa-chevron-right"></i>
            </a>
          </div>
      {% else %}
          <div class="page text-sm lg:text-xs">
            <a href="javascript:void(0);" class="disabled">
              <i class="fa-solid fa-chevron-right"></i>
            </a>
          </div>
      {% endif %}
  {% endif %}
</div>
