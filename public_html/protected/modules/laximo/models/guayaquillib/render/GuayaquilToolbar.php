<?php
class GuayaquilToolbar
{
    static $toolbar;

    var $buttons;

    public static function AddButton($title, $url, $onclick = null, $alt = null, $img = null, $id = null)
    {
        if (!self::$toolbar)
            self::$toolbar = new GuayaquilToolbar();

        self::$toolbar->buttons[] = array('title' => $title, 'url' => $url, 'onclick' => $onclick, 'alt' => $alt, 'img' => $img, 'id' => $id);
    }

    public static function Draw()
    {
        if (!GuayaquilToolbar::$toolbar)
            return '';

        $html = '';

        $toolbar = GuayaquilToolbar::$toolbar;
        foreach ($toolbar->buttons as $button)
            $html .= $toolbar->DrawButton($button);

        return $toolbar->DrawContainer($html);
    }

    private function DrawContainer($content)
    {
        if ($content)
            return '<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b><div id="guayaquil_toolbar" class="xboxcontent">
                    '.$content.'
                </div><b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b><br>';

        return '';
    }

    private function DrawButton($button)
    {
        return '<span class="g_ToolbarButton" '.($button['id'] ? 'id="'.$button['id'].'"' : '').'>
                <a href="'.$button['url'].'" '.($button['onclick'] ? ' onClick="'.$button['onclick'].'"' : '').'>'.
                   ($button['img'] ? '<img src="'.$button['img'].'" alt="'.$button['alt'].'">' : '').' '.
                   $button['title'].'
               </a>
           </span>';
    }
}