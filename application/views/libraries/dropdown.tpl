<%if $options_only eq 1%>
    <%html_options options=$combo_array selected=$combo_selected%>
<%else%>
    <select name="<%$combo_name%>" id="<%$combo_id%>" <%$combo_extra%>>
        <%html_options options=$combo_array selected=$combo_selected%>
    </select>
<%/if%>