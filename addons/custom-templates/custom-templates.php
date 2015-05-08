<?php

class WPUPG_Custom_Templates extends WPUPG_Addon {

    private $mapping;
    private $cache = array();

    public function __construct( $name = 'custom-templates' ) {
        parent::__construct( $name );

        // Load available blocks
        $this->load( 'template' );
        $this->load( 'block' );

        $this->load( 'general/code' );
        $this->load( 'general/date' );
        $this->load( 'general/image' );
        $this->load( 'general/link' );
        $this->load( 'general/paragraph' );
        $this->load( 'general/space' );
        $this->load( 'general/title' );

        $this->load( 'layout/box' );
        $this->load( 'layout/columns' );
        $this->load( 'layout/container' );
        $this->load( 'layout/rows' );
        $this->load( 'layout/table' );

        $this->load( 'post/author' );
        $this->load( 'post/excerpt' );
        $this->load( 'post/image' );
        $this->load( 'post/title' );

        // Actions
        add_action( 'init', array( $this, 'assets' ) );
        add_action( 'init', array( $this, 'default_templates' ) );
    }

    private function load( $block )
    {
        include_once( $this->addonDir . '/templates/' . $block . '.php' );
    }

    public function assets() {

        $fonts = array();
        $templates = array();

        // TODO Get fonts from templates

        foreach( $templates as $template ) {
            if( isset( $template->fonts ) && count( $template->fonts ) > 0 ) {

                $fonts = array_merge(
                    $fonts,
                    $template->fonts
                );
            }
        }

        if( count( $fonts ) > 0 ) {
            WPUltimatePostGrid::get()->helper( 'assets' )->add(
                array(
                    'type' => 'css',
                    'file' => '//fonts.googleapis.com/css?family=' . implode( '|', array_unique( $fonts ) ),
                    'direct' => true,
                    'public' => true,
                )
            );
        }
    }

    public function get_mapping()
    {
        if( !$this->mapping ) {
            $this->mapping = get_option( 'wpupg_custom_template_mapping', array() );
        }
        return $this->mapping;
    }

    public function update_mapping( $mapping )
    {
        $this->mapping = $mapping;
        update_option( 'wpupg_custom_template_mapping', $mapping );
    }

    public function get_template_code( $template )
    {
        $template = intval( $template );

        if( !isset( $this->cache[$template] ) ) {
            $this->cache[$template] = get_option( 'wpupg_custom_template_' . $template, array() );
        }

        return $this->cache[$template];
    }

    public function get_template( $template )
    {
        $mapping = $this->get_mapping();

        // TODO Only return non-default templates for WP Ultimate Post Grid Premium
        if( isset( $mapping[ $template ] ) ) {
            return $this->get_template_code( $template );
        } else {
            return $this->get_template_code( 0 );
        }
    }

    public function default_templates( $reset = false )
    {
        $mapping = $this->get_mapping();

        if( $reset || count( $mapping ) < 1 )
        {
            $templates = array();

            $templates[0] = array(
                'name' => 'Simple',
                'template' => unserialize( base64_decode( 'TzoxNDoiV1BVUEdfVGVtcGxhdGUiOjM6e3M6NjoiYmxvY2tzIjthOjQ6e2k6MDtPOjI0OiJXUFVQR19UZW1wbGF0ZV9Db250YWluZXIiOjE3OntzOjExOiJlZGl0b3JGaWVsZCI7czo5OiJjb250YWluZXIiO3M6NDoidHlwZSI7czo5OiJjb250YWluZXIiO3M6ODoiY2hpbGRyZW4iO2E6MTp7aTowO2E6MTp7aTowO2E6MTp7aTowO086MTk6IldQVVBHX1RlbXBsYXRlX1Jvd3MiOjE5OntzOjQ6InJvd3MiO2k6MjtzOjc6ImhlaWdodHMiO2E6Mjp7aTowO3M6NDoiYXV0byI7aToxO3M6NDoiYXV0byI7fXM6MTE6ImVkaXRvckZpZWxkIjtzOjQ6InJvd3MiO3M6NDoidHlwZSI7czo0OiJyb3dzIjtzOjg6ImNoaWxkcmVuIjthOjI6e2k6MTthOjE6e2k6MDthOjE6e2k6MDtPOjI1OiJXUFVQR19UZW1wbGF0ZV9Qb3N0X1RpdGxlIjoxODp7czozOiJ0YWciO047czoxMToiZWRpdG9yRmllbGQiO3M6OToicG9zdFRpdGxlIjtzOjQ6InR5cGUiO3M6MTA6InBvc3QtdGl0bGUiO3M6ODoiY2hpbGRyZW4iO2E6MDp7fXM6ODoic2V0dGluZ3MiO086ODoic3RkQ2xhc3MiOjQyOntzOjQ6InR5cGUiO3M6OToicG9zdFRpdGxlIjtzOjc6ImRlbGV0ZWQiO2I6MDtzOjU6Im9yZGVyIjtpOjI7czoxMDoiY29uZGl0aW9ucyI7YTowOnt9czo1OiJmbG9hdCI7czo0OiJub25lIjtzOjY6ImNlbnRlciI7YjowO3M6OToibWFyZ2luVG9wIjtzOjE6IjUiO3M6MTI6Im1hcmdpbkJvdHRvbSI7czoxOiI1IjtzOjEwOiJtYXJnaW5MZWZ0IjtzOjE6IjUiO3M6MTE6Im1hcmdpblJpZ2h0IjtzOjE6IjUiO3M6OToid2lkdGhUeXBlIjtzOjI6InB4IjtzOjEwOiJoZWlnaHRUeXBlIjtzOjI6InB4IjtzOjEyOiJtaW5XaWR0aFR5cGUiO3M6MjoicHgiO3M6MTM6Im1pbkhlaWdodFR5cGUiO3M6MjoicHgiO3M6MTI6Im1heFdpZHRoVHlwZSI7czoyOiJweCI7czoxMzoibWF4SGVpZ2h0VHlwZSI7czoyOiJweCI7czo4OiJwb3NpdGlvbiI7czo2OiJzdGF0aWMiO3M6MTE6ImJvcmRlckNvbG9yIjtzOjA6IiI7czoxMToiYm9yZGVyU3R5bGUiO3M6NToic29saWQiO3M6OToiYm9yZGVyVG9wIjtiOjE7czoxMjoiYm9yZGVyQm90dG9tIjtiOjE7czoxMDoiYm9yZGVyTGVmdCI7YjoxO3M6MTE6ImJvcmRlclJpZ2h0IjtiOjE7czoxMDoic2hhZG93VHlwZSI7czowOiIiO3M6OToidGV4dEFsaWduIjtzOjc6ImluaGVyaXQiO3M6MTM6InZlcnRpY2FsQWxpZ24iO3M6NzoiaW5oZXJpdCI7czo4OiJmb250Qm9sZCI7YjoxO3M6MTM6ImZvbnRTbWFsbENhcHMiO2I6MDtzOjg6ImZvbnRTaXplIjtzOjA6IiI7czoxMjoiZm9udFNpemVVbml0IjtzOjI6InB4IjtzOjEwOiJsaW5lSGVpZ2h0IjtzOjA6IiI7czoxNDoibGluZUhlaWdodFVuaXQiO3M6MjoicHgiO3M6OToiZm9udENvbG9yIjtzOjA6IiI7czoxNDoiZm9udEZhbWlseVR5cGUiO3M6MDoiIjtzOjEzOiJmb250RmFtaWx5R1dGIjtzOjk6Ik9wZW4rU2FucyI7czoxMToiY3VzdG9tQ2xhc3MiO3M6MDoiIjtzOjExOiJjdXN0b21TdHlsZSI7czowOiIiO3M6MTE6InBsYWNlaG9sZGVyIjtzOjEwOiJQb3N0IFRpdGxlIjtzOjU6ImluZGV4IjtpOjE7czo2OiJwYXJlbnQiO3M6MToiMiI7czozOiJyb3ciO3M6MToiMSI7czo2OiJjb2x1bW4iO3M6MToiMCI7fXM6NToic3R5bGUiO2E6Mjp7czo3OiJkZWZhdWx0IjthOjg6e3M6MTA6Im1hcmdpbi10b3AiO3M6MzoiNXB4IjtzOjEzOiJtYXJnaW4tYm90dG9tIjtzOjM6IjVweCI7czoxMToibWFyZ2luLWxlZnQiO3M6MzoiNXB4IjtzOjEyOiJtYXJnaW4tcmlnaHQiO3M6MzoiNXB4IjtzOjg6InBvc2l0aW9uIjtzOjY6InN0YXRpYyI7czoxMDoidGV4dC1hbGlnbiI7czo3OiJpbmhlcml0IjtzOjE0OiJ2ZXJ0aWNhbC1hbGlnbiI7czo3OiJpbmhlcml0IjtzOjExOiJmb250LXdlaWdodCI7czo0OiJib2xkIjt9czoyOiJ0ZCI7YToyOntzOjEwOiJ0ZXh0LWFsaWduIjtzOjc6ImluaGVyaXQiO3M6MTE6ImZvbnQtd2VpZ2h0IjtzOjQ6ImJvbGQiO319czoxMDoiY29uZGl0aW9ucyI7YTowOnt9czo3OiJjbGFzc2VzIjthOjA6e31zOjY6InBhcmVudCI7czoxOiIyIjtzOjM6InJvdyI7czoxOiIxIjtzOjY6ImNvbHVtbiI7czoxOiIwIjtzOjU6Im9yZGVyIjtpOjI7czo5OiJtYXhfd2lkdGgiO047czoxMDoibWF4X2hlaWdodCI7TjtzOjE4OiIAKgBzaG93X29uX2Rlc2t0b3AiO2I6MTtzOjE3OiIAKgBzaG93X29uX21vYmlsZSI7YjoxO3M6MTM6IgAqAGxpbmtfY29sb3IiO2I6MDtzOjIwOiIAKgBiYWNrZ3JvdW5kX3ByZXNldCI7YjowO319fWk6MDthOjE6e2k6MDthOjE6e2k6MDtPOjI1OiJXUFVQR19UZW1wbGF0ZV9Qb3N0X0ltYWdlIjoxOTp7czoxMToiZWRpdG9yRmllbGQiO3M6OToicG9zdEltYWdlIjtzOjk6InRodW1ibmFpbCI7czo0OiJmdWxsIjtzOjQ6ImNyb3AiO2I6MDtzOjQ6InR5cGUiO3M6MTA6InBvc3QtaW1hZ2UiO3M6ODoiY2hpbGRyZW4iO2E6MDp7fXM6ODoic2V0dGluZ3MiO086ODoic3RkQ2xhc3MiOjQ0OntzOjQ6InR5cGUiO3M6OToicG9zdEltYWdlIjtzOjc6ImRlbGV0ZWQiO2I6MDtzOjU6Im9yZGVyIjtpOjM7czoxMDoiY29uZGl0aW9ucyI7YTowOnt9czo1OiJmbG9hdCI7czo0OiJub25lIjtzOjY6ImNlbnRlciI7YjowO3M6OToibWFyZ2luVG9wIjtpOjA7czoxMjoibWFyZ2luQm90dG9tIjtpOjA7czoxMDoibWFyZ2luTGVmdCI7aTowO3M6MTE6Im1hcmdpblJpZ2h0IjtpOjA7czo5OiJ3aWR0aFR5cGUiO3M6MjoicHgiO3M6MTA6ImhlaWdodFR5cGUiO3M6MToiJSI7czoxMjoibWluV2lkdGhUeXBlIjtzOjI6InB4IjtzOjEzOiJtaW5IZWlnaHRUeXBlIjtzOjI6InB4IjtzOjEyOiJtYXhXaWR0aFR5cGUiO3M6MjoicHgiO3M6MTM6Im1heEhlaWdodFR5cGUiO3M6MjoicHgiO3M6ODoicG9zaXRpb24iO3M6Njoic3RhdGljIjtzOjExOiJib3JkZXJDb2xvciI7czowOiIiO3M6MTE6ImJvcmRlclN0eWxlIjtzOjU6InNvbGlkIjtzOjk6ImJvcmRlclRvcCI7YjoxO3M6MTI6ImJvcmRlckJvdHRvbSI7YjoxO3M6MTA6ImJvcmRlckxlZnQiO2I6MTtzOjExOiJib3JkZXJSaWdodCI7YjoxO3M6MTA6InNoYWRvd1R5cGUiO3M6MDoiIjtzOjk6InRleHRBbGlnbiI7czo3OiJpbmhlcml0IjtzOjEzOiJ2ZXJ0aWNhbEFsaWduIjtzOjc6ImluaGVyaXQiO3M6ODoiZm9udEJvbGQiO2I6MDtzOjEzOiJmb250U21hbGxDYXBzIjtiOjA7czo4OiJmb250U2l6ZSI7czowOiIiO3M6MTI6ImZvbnRTaXplVW5pdCI7czoyOiJweCI7czoxMDoibGluZUhlaWdodCI7czowOiIiO3M6MTQ6ImxpbmVIZWlnaHRVbml0IjtzOjI6InB4IjtzOjk6ImZvbnRDb2xvciI7czowOiIiO3M6MTQ6ImZvbnRGYW1pbHlUeXBlIjtzOjA6IiI7czoxMzoiZm9udEZhbWlseUdXRiI7czo5OiJPcGVuK1NhbnMiO3M6MTE6ImN1c3RvbUNsYXNzIjtzOjA6IiI7czoxMToiY3VzdG9tU3R5bGUiO3M6MDoiIjtzOjU6IndpZHRoIjtzOjM6IjIwMCI7czo2OiJoZWlnaHQiO3M6MzoiMTAwIjtzOjU6ImluZGV4IjtpOjM7czo2OiJwYXJlbnQiO3M6MToiMiI7czozOiJyb3ciO3M6MToiMCI7czo2OiJjb2x1bW4iO3M6MToiMCI7czo5OiJpbWFnZUNyb3AiO2I6MDt9czo1OiJzdHlsZSI7YToyOntzOjc6ImRlZmF1bHQiO2E6NTp7czo1OiJ3aWR0aCI7czo1OiIyMDBweCI7czo2OiJoZWlnaHQiO3M6NDoiMTAwJSI7czo4OiJwb3NpdGlvbiI7czo2OiJzdGF0aWMiO3M6MTA6InRleHQtYWxpZ24iO3M6NzoiaW5oZXJpdCI7czoxNDoidmVydGljYWwtYWxpZ24iO3M6NzoiaW5oZXJpdCI7fXM6MjoidGQiO2E6MTp7czoxMDoidGV4dC1hbGlnbiI7czo3OiJpbmhlcml0Ijt9fXM6MTA6ImNvbmRpdGlvbnMiO2E6MDp7fXM6NzoiY2xhc3NlcyI7YTowOnt9czo2OiJwYXJlbnQiO3M6MToiMiI7czozOiJyb3ciO3M6MToiMCI7czo2OiJjb2x1bW4iO3M6MToiMCI7czo1OiJvcmRlciI7aTozO3M6OToibWF4X3dpZHRoIjtpOjIwMDtzOjEwOiJtYXhfaGVpZ2h0IjtOO3M6MTg6IgAqAHNob3dfb25fZGVza3RvcCI7YjoxO3M6MTc6IgAqAHNob3dfb25fbW9iaWxlIjtiOjE7czoxMzoiACoAbGlua19jb2xvciI7YjowO3M6MjA6IgAqAGJhY2tncm91bmRfcHJlc2V0IjtiOjA7fX19fXM6ODoic2V0dGluZ3MiO086ODoic3RkQ2xhc3MiOjYyOntzOjQ6InR5cGUiO3M6NDoicm93cyI7czo3OiJkZWxldGVkIjtiOjA7czo1OiJvcmRlciI7aToxO3M6MTA6ImNvbmRpdGlvbnMiO2E6MDp7fXM6NToiZmxvYXQiO3M6NDoibm9uZSI7czo2OiJjZW50ZXIiO2I6MDtzOjk6Im1hcmdpblRvcCI7aTowO3M6MTI6Im1hcmdpbkJvdHRvbSI7aTowO3M6MTA6Im1hcmdpbkxlZnQiO2k6MDtzOjExOiJtYXJnaW5SaWdodCI7aTowO3M6OToid2lkdGhUeXBlIjtzOjI6InB4IjtzOjEwOiJoZWlnaHRUeXBlIjtzOjI6InB4IjtzOjEyOiJtaW5XaWR0aFR5cGUiO3M6MjoicHgiO3M6MTM6Im1pbkhlaWdodFR5cGUiO3M6MjoicHgiO3M6MTI6Im1heFdpZHRoVHlwZSI7czoyOiJweCI7czoxMzoibWF4SGVpZ2h0VHlwZSI7czoyOiJweCI7czo4OiJwb3NpdGlvbiI7czo2OiJzdGF0aWMiO3M6MTE6ImJvcmRlckNvbG9yIjtzOjA6IiI7czoxMToiYm9yZGVyU3R5bGUiO3M6NToic29saWQiO3M6OToiYm9yZGVyVG9wIjtiOjE7czoxMjoiYm9yZGVyQm90dG9tIjtiOjE7czoxMDoiYm9yZGVyTGVmdCI7YjoxO3M6MTE6ImJvcmRlclJpZ2h0IjtiOjE7czoxMDoic2hhZG93VHlwZSI7czowOiIiO3M6OToidGV4dEFsaWduIjtzOjc6ImluaGVyaXQiO3M6MTM6InZlcnRpY2FsQWxpZ24iO3M6NzoiaW5oZXJpdCI7czo4OiJmb250Qm9sZCI7YjowO3M6MTM6ImZvbnRTbWFsbENhcHMiO2I6MDtzOjg6ImZvbnRTaXplIjtzOjA6IiI7czoxMjoiZm9udFNpemVVbml0IjtzOjI6InB4IjtzOjEwOiJsaW5lSGVpZ2h0IjtzOjA6IiI7czoxNDoibGluZUhlaWdodFVuaXQiO3M6MjoicHgiO3M6OToiZm9udENvbG9yIjtzOjA6IiI7czoxNDoiZm9udEZhbWlseVR5cGUiO3M6MDoiIjtzOjEzOiJmb250RmFtaWx5R1dGIjtzOjk6Ik9wZW4rU2FucyI7czoxMToiY3VzdG9tQ2xhc3MiO3M6MDoiIjtzOjExOiJjdXN0b21TdHlsZSI7czowOiIiO3M6NDoicm93cyI7aToyO3M6NDoicm93MCI7aTozMDtzOjQ6InJvdzEiO2k6MzA7czo0OiJyb3cyIjtpOjMwO3M6NDoicm93MyI7aTozMDtzOjQ6InJvdzQiO2k6MzA7czo0OiJyb3c1IjtpOjMwO3M6NDoicm93NiI7aTozMDtzOjQ6InJvdzciO2k6MzA7czo0OiJyb3c4IjtpOjMwO3M6NDoicm93OSI7aTozMDtzOjg6InJvd3R5cGUwIjtzOjU6ImZsdWlkIjtzOjg6InJvd3R5cGUxIjtzOjU6ImZsdWlkIjtzOjg6InJvd3R5cGUyIjtzOjU6ImZsdWlkIjtzOjg6InJvd3R5cGUzIjtzOjU6ImZsdWlkIjtzOjg6InJvd3R5cGU0IjtzOjU6ImZsdWlkIjtzOjg6InJvd3R5cGU1IjtzOjU6ImZsdWlkIjtzOjg6InJvd3R5cGU2IjtzOjU6ImZsdWlkIjtzOjg6InJvd3R5cGU3IjtzOjU6ImZsdWlkIjtzOjg6InJvd3R5cGU4IjtzOjU6ImZsdWlkIjtzOjg6InJvd3R5cGU5IjtzOjU6ImZsdWlkIjtzOjU6ImluZGV4IjtpOjI7czo2OiJwYXJlbnQiO3M6MToiMCI7czozOiJyb3ciO3M6MToiMCI7czo2OiJjb2x1bW4iO3M6MToiMCI7fXM6NToic3R5bGUiO2E6NDp7czo1OiJyb3ctMCI7YToxOntzOjY6ImhlaWdodCI7czo0OiJhdXRvIjt9czo1OiJyb3ctMSI7YToxOntzOjY6ImhlaWdodCI7czo0OiJhdXRvIjt9czo3OiJkZWZhdWx0IjthOjM6e3M6ODoicG9zaXRpb24iO3M6Njoic3RhdGljIjtzOjEwOiJ0ZXh0LWFsaWduIjtzOjc6ImluaGVyaXQiO3M6MTQ6InZlcnRpY2FsLWFsaWduIjtzOjc6ImluaGVyaXQiO31zOjI6InRkIjthOjE6e3M6MTA6InRleHQtYWxpZ24iO3M6NzoiaW5oZXJpdCI7fX1zOjEwOiJjb25kaXRpb25zIjthOjA6e31zOjc6ImNsYXNzZXMiO2E6MDp7fXM6NjoicGFyZW50IjtzOjE6IjAiO3M6Mzoicm93IjtzOjE6IjAiO3M6NjoiY29sdW1uIjtzOjE6IjAiO3M6NToib3JkZXIiO2k6MTtzOjk6Im1heF93aWR0aCI7TjtzOjEwOiJtYXhfaGVpZ2h0IjtOO3M6MTg6IgAqAHNob3dfb25fZGVza3RvcCI7YjoxO3M6MTc6IgAqAHNob3dfb25fbW9iaWxlIjtiOjE7czoxMzoiACoAbGlua19jb2xvciI7YjowO3M6MjA6IgAqAGJhY2tncm91bmRfcHJlc2V0IjtiOjA7fX19fXM6ODoic2V0dGluZ3MiO086ODoic3RkQ2xhc3MiOjQyOntzOjQ6InR5cGUiO3M6OToiY29udGFpbmVyIjtzOjg6Im1heFdpZHRoIjtzOjM6IjIwMCI7czo5OiJtYXJnaW5Ub3AiO3M6MToiNSI7czoxMjoibWFyZ2luQm90dG9tIjtzOjE6IjUiO3M6MTA6Im1hcmdpbkxlZnQiO3M6MToiNSI7czoxMToibWFyZ2luUmlnaHQiO3M6MToiNSI7czo4OiJwb3NpdGlvbiI7czo4OiJhYnNvbHV0ZSI7czo1OiJvcmRlciI7aTotMTtzOjU6ImluZGV4IjtpOjA7czo2OiJwYXJlbnQiO2k6LTE7czoxMDoiY29uZGl0aW9ucyI7YTowOnt9czo1OiJmbG9hdCI7czo0OiJub25lIjtzOjY6ImNlbnRlciI7YjowO3M6OToid2lkdGhUeXBlIjtzOjE6IiUiO3M6MTA6ImhlaWdodFR5cGUiO3M6MjoicHgiO3M6MTI6Im1pbldpZHRoVHlwZSI7czoyOiJweCI7czoxMzoibWluSGVpZ2h0VHlwZSI7czoyOiJweCI7czoxMjoibWF4V2lkdGhUeXBlIjtzOjI6InB4IjtzOjEzOiJtYXhIZWlnaHRUeXBlIjtzOjI6InB4IjtzOjExOiJib3JkZXJDb2xvciI7czoxMzoicmdiYSgwLDAsMCwxKSI7czoxMToiYm9yZGVyU3R5bGUiO3M6NToic29saWQiO3M6OToiYm9yZGVyVG9wIjtiOjE7czoxMjoiYm9yZGVyQm90dG9tIjtiOjE7czoxMDoiYm9yZGVyTGVmdCI7YjoxO3M6MTE6ImJvcmRlclJpZ2h0IjtiOjE7czoxMDoic2hhZG93VHlwZSI7czowOiIiO3M6OToidGV4dEFsaWduIjtzOjc6ImluaGVyaXQiO3M6MTM6InZlcnRpY2FsQWxpZ24iO3M6NzoiaW5oZXJpdCI7czo4OiJmb250Qm9sZCI7YjowO3M6MTM6ImZvbnRTbWFsbENhcHMiO2I6MDtzOjg6ImZvbnRTaXplIjtzOjA6IiI7czoxMjoiZm9udFNpemVVbml0IjtzOjI6InB4IjtzOjEwOiJsaW5lSGVpZ2h0IjtzOjA6IiI7czoxNDoibGluZUhlaWdodFVuaXQiO3M6MjoicHgiO3M6OToiZm9udENvbG9yIjtzOjA6IiI7czoxNDoiZm9udEZhbWlseVR5cGUiO3M6MDoiIjtzOjEzOiJmb250RmFtaWx5R1dGIjtzOjk6Ik9wZW4rU2FucyI7czoxMToiY3VzdG9tQ2xhc3MiO3M6MDoiIjtzOjExOiJjdXN0b21TdHlsZSI7czowOiIiO3M6MTE6ImJvcmRlcldpZHRoIjtzOjE6IjEiO3M6MTA6InBhZGRpbmdUb3AiO3M6MDoiIjtzOjU6IndpZHRoIjtzOjM6IjEwMCI7fXM6NToic3R5bGUiO2E6Mjp7czo3OiJkZWZhdWx0IjthOjExOntzOjEwOiJtYXJnaW4tdG9wIjtzOjM6IjVweCI7czoxMzoibWFyZ2luLWJvdHRvbSI7czozOiI1cHgiO3M6MTE6Im1hcmdpbi1sZWZ0IjtzOjM6IjVweCI7czoxMjoibWFyZ2luLXJpZ2h0IjtzOjM6IjVweCI7czo1OiJ3aWR0aCI7czo0OiIxMDAlIjtzOjk6Im1heC13aWR0aCI7czo1OiIyMDBweCI7czo4OiJwb3NpdGlvbiI7czo4OiJhYnNvbHV0ZSI7czoxMjoiYm9yZGVyLXdpZHRoIjtzOjE1OiIxcHggMXB4IDFweCAxcHgiO3M6MTI6ImJvcmRlci1jb2xvciI7czoxMzoicmdiYSgwLDAsMCwxKSI7czoxMjoiYm9yZGVyLXN0eWxlIjtzOjU6InNvbGlkIjtzOjE0OiJ2ZXJ0aWNhbC1hbGlnbiI7czo3OiJpbmhlcml0Ijt9czoyOiJ0ZCI7YTozOntzOjEyOiJib3JkZXItd2lkdGgiO3M6MzoiMXB4IjtzOjEyOiJib3JkZXItY29sb3IiO3M6MTM6InJnYmEoMCwwLDAsMSkiO3M6MTI6ImJvcmRlci1zdHlsZSI7czo1OiJzb2xpZCI7fX1zOjEwOiJjb25kaXRpb25zIjthOjA6e31zOjc6ImNsYXNzZXMiO2E6MDp7fXM6NjoicGFyZW50IjtpOi0xO3M6Mzoicm93IjtpOjA7czo2OiJjb2x1bW4iO2k6MDtzOjU6Im9yZGVyIjtpOjA7czo5OiJtYXhfd2lkdGgiO2k6MjAwO3M6MTA6Im1heF9oZWlnaHQiO047czoxODoiACoAc2hvd19vbl9kZXNrdG9wIjtiOjE7czoxNzoiACoAc2hvd19vbl9tb2JpbGUiO2I6MTtzOjEzOiIAKgBsaW5rX2NvbG9yIjtiOjA7czoyMDoiACoAYmFja2dyb3VuZF9wcmVzZXQiO2I6MDt9aToxO3I6MTk7aToyO3I6OTtpOjM7cjo5NDt9czo5OiJjb250YWluZXIiO3I6MztzOjU6ImZvbnRzIjthOjA6e319' ) )
            );
            $templates[1] = array(
                'name' => 'Simple with Excerpt',
                'template' => unserialize( base64_decode( 'TzoxNDoiV1BVUEdfVGVtcGxhdGUiOjM6e3M6NjoiYmxvY2tzIjthOjU6e2k6MDtPOjI0OiJXUFVQR19UZW1wbGF0ZV9Db250YWluZXIiOjE3OntzOjExOiJlZGl0b3JGaWVsZCI7czo5OiJjb250YWluZXIiO3M6NDoidHlwZSI7czo5OiJjb250YWluZXIiO3M6ODoiY2hpbGRyZW4iO2E6MTp7aTowO2E6MTp7aTowO2E6MTp7aTowO086MTk6IldQVVBHX1RlbXBsYXRlX1Jvd3MiOjE5OntzOjQ6InJvd3MiO2k6MztzOjc6ImhlaWdodHMiO2E6Mzp7aTowO3M6NDoiYXV0byI7aToxO3M6NDoiYXV0byI7aToyO3M6NDoiYXV0byI7fXM6MTE6ImVkaXRvckZpZWxkIjtzOjQ6InJvd3MiO3M6NDoidHlwZSI7czo0OiJyb3dzIjtzOjg6ImNoaWxkcmVuIjthOjM6e2k6MTthOjE6e2k6MDthOjE6e2k6MDtPOjI1OiJXUFVQR19UZW1wbGF0ZV9Qb3N0X1RpdGxlIjoxODp7czozOiJ0YWciO047czoxMToiZWRpdG9yRmllbGQiO3M6OToicG9zdFRpdGxlIjtzOjQ6InR5cGUiO3M6MTA6InBvc3QtdGl0bGUiO3M6ODoiY2hpbGRyZW4iO2E6MDp7fXM6ODoic2V0dGluZ3MiO086ODoic3RkQ2xhc3MiOjQyOntzOjQ6InR5cGUiO3M6OToicG9zdFRpdGxlIjtzOjc6ImRlbGV0ZWQiO2I6MDtzOjU6Im9yZGVyIjtpOjI7czoxMDoiY29uZGl0aW9ucyI7YTowOnt9czo1OiJmbG9hdCI7czo0OiJub25lIjtzOjY6ImNlbnRlciI7YjowO3M6OToibWFyZ2luVG9wIjtzOjE6IjUiO3M6MTI6Im1hcmdpbkJvdHRvbSI7czoxOiI1IjtzOjEwOiJtYXJnaW5MZWZ0IjtzOjE6IjUiO3M6MTE6Im1hcmdpblJpZ2h0IjtzOjE6IjUiO3M6OToid2lkdGhUeXBlIjtzOjI6InB4IjtzOjEwOiJoZWlnaHRUeXBlIjtzOjI6InB4IjtzOjEyOiJtaW5XaWR0aFR5cGUiO3M6MjoicHgiO3M6MTM6Im1pbkhlaWdodFR5cGUiO3M6MjoicHgiO3M6MTI6Im1heFdpZHRoVHlwZSI7czoyOiJweCI7czoxMzoibWF4SGVpZ2h0VHlwZSI7czoyOiJweCI7czo4OiJwb3NpdGlvbiI7czo2OiJzdGF0aWMiO3M6MTE6ImJvcmRlckNvbG9yIjtzOjA6IiI7czoxMToiYm9yZGVyU3R5bGUiO3M6NToic29saWQiO3M6OToiYm9yZGVyVG9wIjtiOjE7czoxMjoiYm9yZGVyQm90dG9tIjtiOjE7czoxMDoiYm9yZGVyTGVmdCI7YjoxO3M6MTE6ImJvcmRlclJpZ2h0IjtiOjE7czoxMDoic2hhZG93VHlwZSI7czowOiIiO3M6OToidGV4dEFsaWduIjtzOjc6ImluaGVyaXQiO3M6MTM6InZlcnRpY2FsQWxpZ24iO3M6NzoiaW5oZXJpdCI7czo4OiJmb250Qm9sZCI7YjoxO3M6MTM6ImZvbnRTbWFsbENhcHMiO2I6MDtzOjg6ImZvbnRTaXplIjtzOjA6IiI7czoxMjoiZm9udFNpemVVbml0IjtzOjI6InB4IjtzOjEwOiJsaW5lSGVpZ2h0IjtzOjA6IiI7czoxNDoibGluZUhlaWdodFVuaXQiO3M6MjoicHgiO3M6OToiZm9udENvbG9yIjtzOjA6IiI7czoxNDoiZm9udEZhbWlseVR5cGUiO3M6MDoiIjtzOjEzOiJmb250RmFtaWx5R1dGIjtzOjk6Ik9wZW4rU2FucyI7czoxMToiY3VzdG9tQ2xhc3MiO3M6MDoiIjtzOjExOiJjdXN0b21TdHlsZSI7czowOiIiO3M6MTE6InBsYWNlaG9sZGVyIjtzOjEwOiJQb3N0IFRpdGxlIjtzOjU6ImluZGV4IjtpOjE7czo2OiJwYXJlbnQiO3M6MToiMiI7czozOiJyb3ciO3M6MToiMSI7czo2OiJjb2x1bW4iO3M6MToiMCI7fXM6NToic3R5bGUiO2E6Mjp7czo3OiJkZWZhdWx0IjthOjg6e3M6MTA6Im1hcmdpbi10b3AiO3M6MzoiNXB4IjtzOjEzOiJtYXJnaW4tYm90dG9tIjtzOjM6IjVweCI7czoxMToibWFyZ2luLWxlZnQiO3M6MzoiNXB4IjtzOjEyOiJtYXJnaW4tcmlnaHQiO3M6MzoiNXB4IjtzOjg6InBvc2l0aW9uIjtzOjY6InN0YXRpYyI7czoxMDoidGV4dC1hbGlnbiI7czo3OiJpbmhlcml0IjtzOjE0OiJ2ZXJ0aWNhbC1hbGlnbiI7czo3OiJpbmhlcml0IjtzOjExOiJmb250LXdlaWdodCI7czo0OiJib2xkIjt9czoyOiJ0ZCI7YToyOntzOjEwOiJ0ZXh0LWFsaWduIjtzOjc6ImluaGVyaXQiO3M6MTE6ImZvbnQtd2VpZ2h0IjtzOjQ6ImJvbGQiO319czoxMDoiY29uZGl0aW9ucyI7YTowOnt9czo3OiJjbGFzc2VzIjthOjA6e31zOjY6InBhcmVudCI7czoxOiIyIjtzOjM6InJvdyI7czoxOiIxIjtzOjY6ImNvbHVtbiI7czoxOiIwIjtzOjU6Im9yZGVyIjtpOjI7czo5OiJtYXhfd2lkdGgiO047czoxMDoibWF4X2hlaWdodCI7TjtzOjE4OiIAKgBzaG93X29uX2Rlc2t0b3AiO2I6MTtzOjE3OiIAKgBzaG93X29uX21vYmlsZSI7YjoxO3M6MTM6IgAqAGxpbmtfY29sb3IiO2I6MDtzOjIwOiIAKgBiYWNrZ3JvdW5kX3ByZXNldCI7YjowO319fWk6MDthOjE6e2k6MDthOjE6e2k6MDtPOjI1OiJXUFVQR19UZW1wbGF0ZV9Qb3N0X0ltYWdlIjoxOTp7czoxMToiZWRpdG9yRmllbGQiO3M6OToicG9zdEltYWdlIjtzOjk6InRodW1ibmFpbCI7czo0OiJmdWxsIjtzOjQ6ImNyb3AiO2I6MDtzOjQ6InR5cGUiO3M6MTA6InBvc3QtaW1hZ2UiO3M6ODoiY2hpbGRyZW4iO2E6MDp7fXM6ODoic2V0dGluZ3MiO086ODoic3RkQ2xhc3MiOjQ0OntzOjQ6InR5cGUiO3M6OToicG9zdEltYWdlIjtzOjc6ImRlbGV0ZWQiO2I6MDtzOjU6Im9yZGVyIjtpOjM7czoxMDoiY29uZGl0aW9ucyI7YTowOnt9czo1OiJmbG9hdCI7czo0OiJub25lIjtzOjY6ImNlbnRlciI7YjowO3M6OToibWFyZ2luVG9wIjtpOjA7czoxMjoibWFyZ2luQm90dG9tIjtpOjA7czoxMDoibWFyZ2luTGVmdCI7aTowO3M6MTE6Im1hcmdpblJpZ2h0IjtpOjA7czo5OiJ3aWR0aFR5cGUiO3M6MjoicHgiO3M6MTA6ImhlaWdodFR5cGUiO3M6MToiJSI7czoxMjoibWluV2lkdGhUeXBlIjtzOjI6InB4IjtzOjEzOiJtaW5IZWlnaHRUeXBlIjtzOjI6InB4IjtzOjEyOiJtYXhXaWR0aFR5cGUiO3M6MjoicHgiO3M6MTM6Im1heEhlaWdodFR5cGUiO3M6MjoicHgiO3M6ODoicG9zaXRpb24iO3M6Njoic3RhdGljIjtzOjExOiJib3JkZXJDb2xvciI7czowOiIiO3M6MTE6ImJvcmRlclN0eWxlIjtzOjU6InNvbGlkIjtzOjk6ImJvcmRlclRvcCI7YjoxO3M6MTI6ImJvcmRlckJvdHRvbSI7YjoxO3M6MTA6ImJvcmRlckxlZnQiO2I6MTtzOjExOiJib3JkZXJSaWdodCI7YjoxO3M6MTA6InNoYWRvd1R5cGUiO3M6MDoiIjtzOjk6InRleHRBbGlnbiI7czo3OiJpbmhlcml0IjtzOjEzOiJ2ZXJ0aWNhbEFsaWduIjtzOjc6ImluaGVyaXQiO3M6ODoiZm9udEJvbGQiO2I6MDtzOjEzOiJmb250U21hbGxDYXBzIjtiOjA7czo4OiJmb250U2l6ZSI7czowOiIiO3M6MTI6ImZvbnRTaXplVW5pdCI7czoyOiJweCI7czoxMDoibGluZUhlaWdodCI7czowOiIiO3M6MTQ6ImxpbmVIZWlnaHRVbml0IjtzOjI6InB4IjtzOjk6ImZvbnRDb2xvciI7czowOiIiO3M6MTQ6ImZvbnRGYW1pbHlUeXBlIjtzOjA6IiI7czoxMzoiZm9udEZhbWlseUdXRiI7czo5OiJPcGVuK1NhbnMiO3M6MTE6ImN1c3RvbUNsYXNzIjtzOjA6IiI7czoxMToiY3VzdG9tU3R5bGUiO3M6MDoiIjtzOjU6IndpZHRoIjtzOjM6IjIwMCI7czo2OiJoZWlnaHQiO3M6MzoiMTAwIjtzOjU6ImluZGV4IjtpOjM7czo2OiJwYXJlbnQiO3M6MToiMiI7czozOiJyb3ciO3M6MToiMCI7czo2OiJjb2x1bW4iO3M6MToiMCI7czo5OiJpbWFnZUNyb3AiO2I6MDt9czo1OiJzdHlsZSI7YToyOntzOjc6ImRlZmF1bHQiO2E6NTp7czo1OiJ3aWR0aCI7czo1OiIyMDBweCI7czo2OiJoZWlnaHQiO3M6NDoiMTAwJSI7czo4OiJwb3NpdGlvbiI7czo2OiJzdGF0aWMiO3M6MTA6InRleHQtYWxpZ24iO3M6NzoiaW5oZXJpdCI7czoxNDoidmVydGljYWwtYWxpZ24iO3M6NzoiaW5oZXJpdCI7fXM6MjoidGQiO2E6MTp7czoxMDoidGV4dC1hbGlnbiI7czo3OiJpbmhlcml0Ijt9fXM6MTA6ImNvbmRpdGlvbnMiO2E6MDp7fXM6NzoiY2xhc3NlcyI7YTowOnt9czo2OiJwYXJlbnQiO3M6MToiMiI7czozOiJyb3ciO3M6MToiMCI7czo2OiJjb2x1bW4iO3M6MToiMCI7czo1OiJvcmRlciI7aTozO3M6OToibWF4X3dpZHRoIjtpOjIwMDtzOjEwOiJtYXhfaGVpZ2h0IjtOO3M6MTg6IgAqAHNob3dfb25fZGVza3RvcCI7YjoxO3M6MTc6IgAqAHNob3dfb25fbW9iaWxlIjtiOjE7czoxMzoiACoAbGlua19jb2xvciI7YjowO3M6MjA6IgAqAGJhY2tncm91bmRfcHJlc2V0IjtiOjA7fX19aToyO2E6MTp7aTowO2E6MTp7aTowO086Mjc6IldQVVBHX1RlbXBsYXRlX1Bvc3RfRXhjZXJwdCI6MTc6e3M6MTE6ImVkaXRvckZpZWxkIjtzOjExOiJwb3N0RXhjZXJwdCI7czo0OiJ0eXBlIjtzOjEyOiJwb3N0LWV4Y2VycHQiO3M6ODoiY2hpbGRyZW4iO2E6MDp7fXM6ODoic2V0dGluZ3MiO086ODoic3RkQ2xhc3MiOjQyOntzOjQ6InR5cGUiO3M6MTE6InBvc3RFeGNlcnB0IjtzOjc6ImRlbGV0ZWQiO2I6MDtzOjU6Im9yZGVyIjtpOjQ7czoxMDoiY29uZGl0aW9ucyI7YTowOnt9czo1OiJmbG9hdCI7czo0OiJub25lIjtzOjY6ImNlbnRlciI7YjowO3M6OToibWFyZ2luVG9wIjtzOjE6IjAiO3M6MTI6Im1hcmdpbkJvdHRvbSI7czoxOiI1IjtzOjEwOiJtYXJnaW5MZWZ0IjtzOjE6IjUiO3M6MTE6Im1hcmdpblJpZ2h0IjtzOjE6IjUiO3M6OToid2lkdGhUeXBlIjtzOjI6InB4IjtzOjEwOiJoZWlnaHRUeXBlIjtzOjI6InB4IjtzOjEyOiJtaW5XaWR0aFR5cGUiO3M6MjoicHgiO3M6MTM6Im1pbkhlaWdodFR5cGUiO3M6MjoicHgiO3M6MTI6Im1heFdpZHRoVHlwZSI7czoyOiJweCI7czoxMzoibWF4SGVpZ2h0VHlwZSI7czoyOiJweCI7czo4OiJwb3NpdGlvbiI7czo2OiJzdGF0aWMiO3M6MTE6ImJvcmRlckNvbG9yIjtzOjA6IiI7czoxMToiYm9yZGVyU3R5bGUiO3M6NToic29saWQiO3M6OToiYm9yZGVyVG9wIjtiOjE7czoxMjoiYm9yZGVyQm90dG9tIjtiOjE7czoxMDoiYm9yZGVyTGVmdCI7YjoxO3M6MTE6ImJvcmRlclJpZ2h0IjtiOjE7czoxMDoic2hhZG93VHlwZSI7czowOiIiO3M6OToidGV4dEFsaWduIjtzOjc6ImluaGVyaXQiO3M6MTM6InZlcnRpY2FsQWxpZ24iO3M6NzoiaW5oZXJpdCI7czo4OiJmb250Qm9sZCI7YjowO3M6MTM6ImZvbnRTbWFsbENhcHMiO2I6MDtzOjg6ImZvbnRTaXplIjtzOjM6IjAuOSI7czoxMjoiZm9udFNpemVVbml0IjtzOjI6ImVtIjtzOjEwOiJsaW5lSGVpZ2h0IjtzOjA6IiI7czoxNDoibGluZUhlaWdodFVuaXQiO3M6MjoicHgiO3M6OToiZm9udENvbG9yIjtzOjE2OiJyZ2JhKDc5LDc5LDc5LDEpIjtzOjE0OiJmb250RmFtaWx5VHlwZSI7czowOiIiO3M6MTM6ImZvbnRGYW1pbHlHV0YiO3M6OToiT3BlbitTYW5zIjtzOjExOiJjdXN0b21DbGFzcyI7czowOiIiO3M6MTE6ImN1c3RvbVN0eWxlIjtzOjA6IiI7czoxMToicGxhY2Vob2xkZXIiO3M6MTI6IlBvc3QgRXhjZXJwdCI7czo1OiJpbmRleCI7aTo0O3M6NjoicGFyZW50IjtzOjE6IjIiO3M6Mzoicm93IjtzOjE6IjIiO3M6NjoiY29sdW1uIjtzOjE6IjAiO31zOjU6InN0eWxlIjthOjI6e3M6NzoiZGVmYXVsdCI7YTo5OntzOjEwOiJtYXJnaW4tdG9wIjtzOjM6IjBweCI7czoxMzoibWFyZ2luLWJvdHRvbSI7czozOiI1cHgiO3M6MTE6Im1hcmdpbi1sZWZ0IjtzOjM6IjVweCI7czoxMjoibWFyZ2luLXJpZ2h0IjtzOjM6IjVweCI7czo4OiJwb3NpdGlvbiI7czo2OiJzdGF0aWMiO3M6MTA6InRleHQtYWxpZ24iO3M6NzoiaW5oZXJpdCI7czoxNDoidmVydGljYWwtYWxpZ24iO3M6NzoiaW5oZXJpdCI7czo5OiJmb250LXNpemUiO3M6NToiMC45ZW0iO3M6NToiY29sb3IiO3M6MTY6InJnYmEoNzksNzksNzksMSkiO31zOjI6InRkIjthOjE6e3M6MTA6InRleHQtYWxpZ24iO3M6NzoiaW5oZXJpdCI7fX1zOjEwOiJjb25kaXRpb25zIjthOjA6e31zOjc6ImNsYXNzZXMiO2E6MDp7fXM6NjoicGFyZW50IjtzOjE6IjIiO3M6Mzoicm93IjtzOjE6IjIiO3M6NjoiY29sdW1uIjtzOjE6IjAiO3M6NToib3JkZXIiO2k6NDtzOjk6Im1heF93aWR0aCI7TjtzOjEwOiJtYXhfaGVpZ2h0IjtOO3M6MTg6IgAqAHNob3dfb25fZGVza3RvcCI7YjoxO3M6MTc6IgAqAHNob3dfb25fbW9iaWxlIjtiOjE7czoxMzoiACoAbGlua19jb2xvciI7YjowO3M6MjA6IgAqAGJhY2tncm91bmRfcHJlc2V0IjtiOjA7fX19fXM6ODoic2V0dGluZ3MiO086ODoic3RkQ2xhc3MiOjYyOntzOjQ6InR5cGUiO3M6NDoicm93cyI7czo3OiJkZWxldGVkIjtiOjA7czo1OiJvcmRlciI7aToxO3M6MTA6ImNvbmRpdGlvbnMiO2E6MTp7aTowO086ODoic3RkQ2xhc3MiOjQ6e3M6MTQ6ImNvbmRpdGlvbl90eXBlIjtzOjU6ImZpZWxkIjtzOjY6InRhcmdldCI7czo1OiJyb3ctMiI7czo1OiJmaWVsZCI7czoxMjoicG9zdF9leGNlcnB0IjtzOjQ6IndoZW4iO3M6NzoibWlzc2luZyI7fX1zOjU6ImZsb2F0IjtzOjQ6Im5vbmUiO3M6NjoiY2VudGVyIjtiOjA7czo5OiJtYXJnaW5Ub3AiO2k6MDtzOjEyOiJtYXJnaW5Cb3R0b20iO2k6MDtzOjEwOiJtYXJnaW5MZWZ0IjtpOjA7czoxMToibWFyZ2luUmlnaHQiO2k6MDtzOjk6IndpZHRoVHlwZSI7czoyOiJweCI7czoxMDoiaGVpZ2h0VHlwZSI7czoyOiJweCI7czoxMjoibWluV2lkdGhUeXBlIjtzOjI6InB4IjtzOjEzOiJtaW5IZWlnaHRUeXBlIjtzOjI6InB4IjtzOjEyOiJtYXhXaWR0aFR5cGUiO3M6MjoicHgiO3M6MTM6Im1heEhlaWdodFR5cGUiO3M6MjoicHgiO3M6ODoicG9zaXRpb24iO3M6Njoic3RhdGljIjtzOjExOiJib3JkZXJDb2xvciI7czowOiIiO3M6MTE6ImJvcmRlclN0eWxlIjtzOjU6InNvbGlkIjtzOjk6ImJvcmRlclRvcCI7YjoxO3M6MTI6ImJvcmRlckJvdHRvbSI7YjoxO3M6MTA6ImJvcmRlckxlZnQiO2I6MTtzOjExOiJib3JkZXJSaWdodCI7YjoxO3M6MTA6InNoYWRvd1R5cGUiO3M6MDoiIjtzOjk6InRleHRBbGlnbiI7czo3OiJpbmhlcml0IjtzOjEzOiJ2ZXJ0aWNhbEFsaWduIjtzOjc6ImluaGVyaXQiO3M6ODoiZm9udEJvbGQiO2I6MDtzOjEzOiJmb250U21hbGxDYXBzIjtiOjA7czo4OiJmb250U2l6ZSI7czowOiIiO3M6MTI6ImZvbnRTaXplVW5pdCI7czoyOiJweCI7czoxMDoibGluZUhlaWdodCI7czowOiIiO3M6MTQ6ImxpbmVIZWlnaHRVbml0IjtzOjI6InB4IjtzOjk6ImZvbnRDb2xvciI7czowOiIiO3M6MTQ6ImZvbnRGYW1pbHlUeXBlIjtzOjA6IiI7czoxMzoiZm9udEZhbWlseUdXRiI7czo5OiJPcGVuK1NhbnMiO3M6MTE6ImN1c3RvbUNsYXNzIjtzOjA6IiI7czoxMToiY3VzdG9tU3R5bGUiO3M6MDoiIjtzOjQ6InJvd3MiO2k6MztzOjQ6InJvdzAiO2k6MzA7czo0OiJyb3cxIjtpOjMwO3M6NDoicm93MiI7aTozMDtzOjQ6InJvdzMiO2k6MzA7czo0OiJyb3c0IjtpOjMwO3M6NDoicm93NSI7aTozMDtzOjQ6InJvdzYiO2k6MzA7czo0OiJyb3c3IjtpOjMwO3M6NDoicm93OCI7aTozMDtzOjQ6InJvdzkiO2k6MzA7czo4OiJyb3d0eXBlMCI7czo1OiJmbHVpZCI7czo4OiJyb3d0eXBlMSI7czo1OiJmbHVpZCI7czo4OiJyb3d0eXBlMiI7czo1OiJmbHVpZCI7czo4OiJyb3d0eXBlMyI7czo1OiJmbHVpZCI7czo4OiJyb3d0eXBlNCI7czo1OiJmbHVpZCI7czo4OiJyb3d0eXBlNSI7czo1OiJmbHVpZCI7czo4OiJyb3d0eXBlNiI7czo1OiJmbHVpZCI7czo4OiJyb3d0eXBlNyI7czo1OiJmbHVpZCI7czo4OiJyb3d0eXBlOCI7czo1OiJmbHVpZCI7czo4OiJyb3d0eXBlOSI7czo1OiJmbHVpZCI7czo1OiJpbmRleCI7aToyO3M6NjoicGFyZW50IjtzOjE6IjAiO3M6Mzoicm93IjtzOjE6IjAiO3M6NjoiY29sdW1uIjtzOjE6IjAiO31zOjU6InN0eWxlIjthOjU6e3M6NToicm93LTAiO2E6MTp7czo2OiJoZWlnaHQiO3M6NDoiYXV0byI7fXM6NToicm93LTEiO2E6MTp7czo2OiJoZWlnaHQiO3M6NDoiYXV0byI7fXM6NToicm93LTIiO2E6MTp7czo2OiJoZWlnaHQiO3M6NDoiYXV0byI7fXM6NzoiZGVmYXVsdCI7YTozOntzOjg6InBvc2l0aW9uIjtzOjY6InN0YXRpYyI7czoxMDoidGV4dC1hbGlnbiI7czo3OiJpbmhlcml0IjtzOjE0OiJ2ZXJ0aWNhbC1hbGlnbiI7czo3OiJpbmhlcml0Ijt9czoyOiJ0ZCI7YToxOntzOjEwOiJ0ZXh0LWFsaWduIjtzOjc6ImluaGVyaXQiO319czoxMDoiY29uZGl0aW9ucyI7YToxOntzOjU6InJvdy0yIjthOjE6e2k6MDthOjQ6e3M6NDoidHlwZSI7czo0OiJoaWRlIjtzOjE0OiJjb25kaXRpb25fdHlwZSI7czo1OiJmaWVsZCI7czo1OiJmaWVsZCI7czoxMjoicG9zdF9leGNlcnB0IjtzOjQ6IndoZW4iO3M6NzoibWlzc2luZyI7fX19czo3OiJjbGFzc2VzIjthOjA6e31zOjY6InBhcmVudCI7czoxOiIwIjtzOjM6InJvdyI7czoxOiIwIjtzOjY6ImNvbHVtbiI7czoxOiIwIjtzOjU6Im9yZGVyIjtpOjE7czo5OiJtYXhfd2lkdGgiO047czoxMDoibWF4X2hlaWdodCI7TjtzOjE4OiIAKgBzaG93X29uX2Rlc2t0b3AiO2I6MTtzOjE3OiIAKgBzaG93X29uX21vYmlsZSI7YjoxO3M6MTM6IgAqAGxpbmtfY29sb3IiO2I6MDtzOjIwOiIAKgBiYWNrZ3JvdW5kX3ByZXNldCI7YjowO319fX1zOjg6InNldHRpbmdzIjtPOjg6InN0ZENsYXNzIjo0Mjp7czo0OiJ0eXBlIjtzOjk6ImNvbnRhaW5lciI7czo4OiJtYXhXaWR0aCI7czozOiIyMDAiO3M6OToibWFyZ2luVG9wIjtzOjE6IjUiO3M6MTI6Im1hcmdpbkJvdHRvbSI7czoxOiI1IjtzOjEwOiJtYXJnaW5MZWZ0IjtzOjE6IjUiO3M6MTE6Im1hcmdpblJpZ2h0IjtzOjE6IjUiO3M6ODoicG9zaXRpb24iO3M6ODoiYWJzb2x1dGUiO3M6NToib3JkZXIiO2k6LTE7czo1OiJpbmRleCI7aTowO3M6NjoicGFyZW50IjtpOi0xO3M6MTA6ImNvbmRpdGlvbnMiO2E6MDp7fXM6NToiZmxvYXQiO3M6NDoibm9uZSI7czo2OiJjZW50ZXIiO2I6MDtzOjk6IndpZHRoVHlwZSI7czoxOiIlIjtzOjEwOiJoZWlnaHRUeXBlIjtzOjI6InB4IjtzOjEyOiJtaW5XaWR0aFR5cGUiO3M6MjoicHgiO3M6MTM6Im1pbkhlaWdodFR5cGUiO3M6MjoicHgiO3M6MTI6Im1heFdpZHRoVHlwZSI7czoyOiJweCI7czoxMzoibWF4SGVpZ2h0VHlwZSI7czoyOiJweCI7czoxMToiYm9yZGVyQ29sb3IiO3M6MTM6InJnYmEoMCwwLDAsMSkiO3M6MTE6ImJvcmRlclN0eWxlIjtzOjU6InNvbGlkIjtzOjk6ImJvcmRlclRvcCI7YjoxO3M6MTI6ImJvcmRlckJvdHRvbSI7YjoxO3M6MTA6ImJvcmRlckxlZnQiO2I6MTtzOjExOiJib3JkZXJSaWdodCI7YjoxO3M6MTA6InNoYWRvd1R5cGUiO3M6MDoiIjtzOjk6InRleHRBbGlnbiI7czo3OiJpbmhlcml0IjtzOjEzOiJ2ZXJ0aWNhbEFsaWduIjtzOjc6ImluaGVyaXQiO3M6ODoiZm9udEJvbGQiO2I6MDtzOjEzOiJmb250U21hbGxDYXBzIjtiOjA7czo4OiJmb250U2l6ZSI7czowOiIiO3M6MTI6ImZvbnRTaXplVW5pdCI7czoyOiJweCI7czoxMDoibGluZUhlaWdodCI7czowOiIiO3M6MTQ6ImxpbmVIZWlnaHRVbml0IjtzOjI6InB4IjtzOjk6ImZvbnRDb2xvciI7czowOiIiO3M6MTQ6ImZvbnRGYW1pbHlUeXBlIjtzOjA6IiI7czoxMzoiZm9udEZhbWlseUdXRiI7czo5OiJPcGVuK1NhbnMiO3M6MTE6ImN1c3RvbUNsYXNzIjtzOjA6IiI7czoxMToiY3VzdG9tU3R5bGUiO3M6MDoiIjtzOjExOiJib3JkZXJXaWR0aCI7czoxOiIxIjtzOjEwOiJwYWRkaW5nVG9wIjtzOjA6IiI7czo1OiJ3aWR0aCI7czozOiIxMDAiO31zOjU6InN0eWxlIjthOjI6e3M6NzoiZGVmYXVsdCI7YToxMTp7czoxMDoibWFyZ2luLXRvcCI7czozOiI1cHgiO3M6MTM6Im1hcmdpbi1ib3R0b20iO3M6MzoiNXB4IjtzOjExOiJtYXJnaW4tbGVmdCI7czozOiI1cHgiO3M6MTI6Im1hcmdpbi1yaWdodCI7czozOiI1cHgiO3M6NToid2lkdGgiO3M6NDoiMTAwJSI7czo5OiJtYXgtd2lkdGgiO3M6NToiMjAwcHgiO3M6ODoicG9zaXRpb24iO3M6ODoiYWJzb2x1dGUiO3M6MTI6ImJvcmRlci13aWR0aCI7czoxNToiMXB4IDFweCAxcHggMXB4IjtzOjEyOiJib3JkZXItY29sb3IiO3M6MTM6InJnYmEoMCwwLDAsMSkiO3M6MTI6ImJvcmRlci1zdHlsZSI7czo1OiJzb2xpZCI7czoxNDoidmVydGljYWwtYWxpZ24iO3M6NzoiaW5oZXJpdCI7fXM6MjoidGQiO2E6Mzp7czoxMjoiYm9yZGVyLXdpZHRoIjtzOjM6IjFweCI7czoxMjoiYm9yZGVyLWNvbG9yIjtzOjEzOiJyZ2JhKDAsMCwwLDEpIjtzOjEyOiJib3JkZXItc3R5bGUiO3M6NToic29saWQiO319czoxMDoiY29uZGl0aW9ucyI7YTowOnt9czo3OiJjbGFzc2VzIjthOjA6e31zOjY6InBhcmVudCI7aTotMTtzOjM6InJvdyI7aTowO3M6NjoiY29sdW1uIjtpOjA7czo1OiJvcmRlciI7aTowO3M6OToibWF4X3dpZHRoIjtpOjIwMDtzOjEwOiJtYXhfaGVpZ2h0IjtOO3M6MTg6IgAqAHNob3dfb25fZGVza3RvcCI7YjoxO3M6MTc6IgAqAHNob3dfb25fbW9iaWxlIjtiOjE7czoxMzoiACoAbGlua19jb2xvciI7YjowO3M6MjA6IgAqAGJhY2tncm91bmRfcHJlc2V0IjtiOjA7fWk6MTtyOjIwO2k6MjtyOjk7aTozO3I6OTU7aTo0O3I6MTY5O31zOjk6ImNvbnRhaW5lciI7cjozO3M6NToiZm9udHMiO2E6MDp7fX0=' ) )
            );

            // Set default options
            foreach( $templates as $id => $template ) {
                $mapping[$id] = $template['name'];
                update_option( 'wpupg_custom_template_' . intval( $id ), $template['template'] );
            }

            $this->update_mapping( $mapping );
        }
    }

    public function add_template( $name, $template )
    {
        $mapping = $this->get_mapping();

        $template_id = max( array_keys( $mapping ) ) + 1;
        if( $template_id < 100 ) $template_id = 100;

        $mapping[$template_id] = $name;

        $this->update_mapping( $mapping );
        update_option( 'wpupg_custom_template_' . $template_id, $template );

        return $template_id;
    }

    public function update_template( $id, $template )
    {
        update_option( 'wpupg_custom_template_' . $id, $template );
    }

    public function delete_template( $id )
    {
        $mapping = $this->get_mapping();
        unset( $mapping[$id] );

        $this->update_mapping( $mapping );
        delete_option( 'wpupg_custom_template_' . $id );
    }
}

WPUltimatePostGrid::loaded_addon( 'custom-templates', new WPUPG_Custom_Templates() );