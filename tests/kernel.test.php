<?php
/* 
 * Test for kernel
 */

/*
 * Test for function _kernel_alias_list
 */
$_kernel_alias_list[]=array(
        'args'=>array('/test'),
        'etalon'=>'/',
        'description'=>'Test alias for exist'
);
$_kernel_alias_list[]=array(
        'args'=>array('/notexist'),
        'etalon'=>false,
        'description'=>'Test alias for not exist'
);

/*
 * Test for function _kernel_get_domain
 */
$_kernel_get_domain[]=array(
        'args'=>array('ubart.ru'),
        'etalon'=>'ubart.ru',
        'description'=>'Test domain w/o www'
);
$_kernel_get_domain[]=array(
        'args'=>array('www.ubart.ru'),
        'etalon'=>'ubart.ru',
        'description'=>'Test domain with www'
);

/*
 * Test for function _kernel_get_page
 */
$_kernel_get_page[]=array(
        'args'=>array('/test'),
        'etalon'=>array(
            'page'=>array(
                'headers'=>array(0=>array(
                        'header'=> 'Location: /',
                        'replace'=>true,
                        'code'=>'301'
                    )
                )
            )
        ),
        'description'=>'Test page with redirect'
);
$_kernel_get_page[]=array(
        'args'=>array('/'),
        'etalon'=>array(
            'page'=>array(
                'id'=>1,
                'uri'=>'/',
                'min_args'=>'0',
                'max_args'=>'0',
                'template'=>'index',
                'create_time'=>'0',
                'arg_names'=>'',
                'placeholdes'=>serialize(array(
                    'TITLE'=>'Main page',
                    'CONTENT'=>'Content'
                ))
            )
         ),
        'description'=>'Test page w/o args'
);
$_kernel_get_page[]=array(
        'args'=>array('/new/1/2'),
        'etalon'=>array(
            'page'=>array(
                'id'=>2,
                'uri'=>'/new',
                'min_args'=>'2',
                'max_args'=>'2',
                'template'=>'index',
                'create_time'=>'0',
                'arg_names'=>'arg1,arg2',
                'placeholdes'=>serialize(array(
                    'TITLE'=>'New page',
                    'CONTENT'=>'Content'
                ))
            ),
            'args'=>array(
                'arg1'=>'1',
                'arg2'=>'2'
            )
         ),
        'description'=>'Test page with 2 args (2 needed)'
);
$_kernel_get_page[]=array(
        'args'=>array('/new/1'),
        'etalon'=>false,
        'description'=>'Test page with 1 args (2 needed)'
);
$_kernel_get_page[]=array(
        'args'=>array('/new/1/2/3'),
        'etalon'=>false,
        'description'=>'Test page with 3 args (2 needed)'
);
$_kernel_get_page[]=array(
        'args'=>array('/new/1//2'),
        'etalon'=>array(
            'page'=>array(
                'headers'=>array(0=>array(
                        'header'=> 'Location: /new/1/2',
                        'replace'=>true,
                        'code'=>'301'
                    )
                )
            )
        ),
        'description'=>'Test page with double / (must be redirected to normal page)'
);
$_kernel_get_page[]=array(
        'args'=>array('/about/1'),
        'etalon'=>array(
            'page'=>array(
                'id'=>3,
                'uri'=>'/about',
                'min_args'=>'0',
                'max_args'=>'2',
                'template'=>'index',
                'create_time'=>'0',
                'arg_names'=>'arg1,arg2',
                'placeholdes'=>serialize(array(
                    'TITLE'=>'About page',
                    'CONTENT'=>'Content'
                ))
            ),
            'args'=>array(
                'arg1'=>'1'
            )
         ),
        'description'=>'Test page with 1 args (0-2 needed)'
);
$_kernel_get_page[]=array(
        'args'=>array('/about'),
        'etalon'=>array(
            'page'=>array(
                'id'=>3,
                'uri'=>'/about',
                'min_args'=>'0',
                'max_args'=>'2',
                'template'=>'index',
                'create_time'=>'0',
                'arg_names'=>'arg1,arg2',
                'placeholdes'=>serialize(array(
                    'TITLE'=>'About page',
                    'CONTENT'=>'Content'
                ))
            )
         ),
        'description'=>'Test page with 0 args (0-2 needed)'
);
$_kernel_get_page[]=array(
        'args'=>array('/about/1/2/3'),
        'etalon'=>false,
        'description'=>'Test page with 3 args (0-2 needed)'
);

/*
 * Test for function _kernel_parse_page
 */
$_kernel_parse_page[]=array(
        'args'=>array('/'),
        'etalon'=>array(
            'page'=>array(
                'id'=>1,
                'uri'=>'/',
                'min_args'=>'0',
                'max_args'=>'0',
                'template'=>'index',
                'create_time'=>'0',
                'arg_names'=>'',
                'placeholdes'=>serialize(array(
                    'TITLE'=>'Main page',
                    'CONTENT'=>'Content'
                ))
            )
        ),
        'description'=>'Test parsing main page'
);
$_kernel_parse_page[]=array(
        'args'=>array('/new/1/2'),
        'etalon'=>array(
            'page'=>array(
                'id'=>2,
                'uri'=>'/new',
                'min_args'=>'2',
                'max_args'=>'2',
                'template'=>'index',
                'create_time'=>'0',
                'arg_names'=>'arg1,arg2',
                'placeholdes'=>serialize(array(
                    'TITLE'=>'New page',
                    'CONTENT'=>'Content'
                ))
            ),
            'args'=>array(
                'arg1'=>'1',
                'arg2'=>'2'
            )
        ),
        'description'=>'Test parsing /new  page with 2 params'
);
$_kernel_parse_page[]=array(
        'args'=>array('/new/1'),
        'etalon'=>false,
        'description'=>'Test parsing /new  page with 1 params'
);
$_kernel_parse_page[]=array(
        'args'=>array('/new/1/2/3'),
        'etalon'=>false,
        'description'=>'Test parsing /new  page with 3 params'
);
$_kernel_parse_page[]=array(
        'args'=>array('/about/1/2'),
        'etalon'=>array(
            'page'=>array(
                'id'=>3,
                'uri'=>'/about',
                'min_args'=>'0',
                'max_args'=>'2',
                'template'=>'index',
                'create_time'=>'0',
                'arg_names'=>'arg1,arg2',
                'placeholdes'=>serialize(array(
                    'TITLE'=>'About page',
                    'CONTENT'=>'Content'
                ))
            ),
            'args'=>array(
                'arg1'=>'1',
                'arg2'=>'2'
            )
        ),
        'description'=>'Test parsing /about page with 2 params'
);
$_kernel_parse_page[]=array(
        'args'=>array('/about/1'),
        'etalon'=>array(
            'page'=>array(
                'id'=>3,
                'uri'=>'/about',
                'min_args'=>'0',
                'max_args'=>'2',
                'template'=>'index',
                'create_time'=>'0',
                'arg_names'=>'arg1,arg2',
                'placeholdes'=>serialize(array(
                    'TITLE'=>'About page',
                    'CONTENT'=>'Content'
                ))
            ),
            'args'=>array(
                'arg1'=>'1'
            )
        ),
        'description'=>'Test parsing /about page with 1 params'
);
$_kernel_parse_page[]=array(
        'args'=>array('/about'),
        'etalon'=>array(
            'page'=>array(
                'id'=>3,
                'uri'=>'/about',
                'min_args'=>'0',
                'max_args'=>'2',
                'template'=>'index',
                'create_time'=>'0',
                'arg_names'=>'arg1,arg2',
                'placeholdes'=>serialize(array(
                    'TITLE'=>'About page',
                    'CONTENT'=>'Content'
                ))
            ),
        ),
        'description'=>'Test parsing /about page with 0 params'
);
$_kernel_parse_page[]=array(
        'args'=>array('/noone'),
        'etalon'=>FALSE,
        'description'=>'Test parsing non-existent page'
);

/*
 * Test for function _kernel_get_page_list
 */
$_kernel_get_page_list[]=array(        
        'etalon'=>array(
            '/'=>array('uri'=>'/','id'=>1),
            '/new'=>array('uri'=>'/new','id'=>2),
            '/about'=>array('uri'=>'/about','id'=>3),
        ),
        'description'=>'Test page list'
);

/*
 * Test for function _kernel_get_page_data
 */
$_kernel_get_page_data[]=array(
        'args'=>array(1),
        'etalon'=>array(
            'page'=>array(
                'id'=>1,
                'uri'=>'/',
                'min_args'=>'0',
                'max_args'=>'0',
                'template'=>'index',
                'create_time'=>'0',
                'arg_names'=>'',
                'placeholdes'=>serialize(array(
                'TITLE'=>'Main page',
                'CONTENT'=>'Content'
        ))
             ),
        ),
        'description'=>'Test page data (no args)'
);
$_kernel_get_page_data[]=array(
        'args'=>array(2,array(1,2)),
        'etalon'=>array(
            'page'=>array(
                'id'=>2,
                'uri'=>'/new',
                'min_args'=>'2',
                'max_args'=>'2',
                'template'=>'index',
                'create_time'=>'0',
                'arg_names'=>'arg1,arg2',
                'placeholdes'=>serialize(array(
                'TITLE'=>'New page',
                'CONTENT'=>'Content'
        ))
             ),
            'args'=>array('arg1'=>1,'arg2'=>2)
        ),
        'description'=>'Test page data 2 args with 2 needed'
);
$_kernel_get_page_data[]=array(
        'args'=>array(2,array(1)),
        'etalon'=>false,
        'description'=>'Test page data 1 args with 2 needed'
);
$_kernel_get_page_data[]=array(
        'args'=>array(2,array(1,2,3)),
        'etalon'=>false,
        'description'=>'Test page data 3 args with 2 needed'
);
$_kernel_get_page_data[]=array(
        'args'=>array(3,array(1,2)),
        'etalon'=>array(
            'page'=>array(
                'id'=>3,
                'uri'=>'/about',
                'min_args'=>'0',
                'max_args'=>'2',
                'template'=>'index',
                'create_time'=>'0',
                'arg_names'=>'arg1,arg2',
                'placeholdes'=>serialize(array(
                'TITLE'=>'About page',
                'CONTENT'=>'Content'
        ))
             ),
            'args'=>array('arg1'=>1,'arg2'=>2)
        ),
        'description'=>'Test page data 2 args with 0-2 needed'
);
$_kernel_get_page_data[]=array(
        'args'=>array(3,array(1)),
        'etalon'=>array(
            'page'=>array(
                'id'=>3,
                'uri'=>'/about',
                'min_args'=>'0',
                'max_args'=>'2',
                'template'=>'index',
                'create_time'=>'0',
                'arg_names'=>'arg1,arg2',
                'placeholdes'=>serialize(array(
                'TITLE'=>'About page',
                'CONTENT'=>'Content'
        ))
             ),
            'args'=>array('arg1'=>1)
        ),
        'description'=>'Test page data 1 args with 0-2 needed'
);
$_kernel_get_page_data[]=array(
        'args'=>array(3),
        'etalon'=>array(
            'page'=>array(
                'id'=>3,
                'uri'=>'/about',
                'min_args'=>'0',
                'max_args'=>'2',
                'template'=>'index',
                'create_time'=>'0',
                'arg_names'=>'arg1,arg2',
                'placeholdes'=>serialize(array(
                'TITLE'=>'About page',
                'CONTENT'=>'Content'
        ))
             ),
        ),
        'description'=>'Test page data 0 args with 0-2 needed'
);

/*
 * Test for function _kernel_make_args
 */
$_kernel_make_args[]=array(
        'args'=>array(array(123),'arg1'),
        'etalon'=>array(
            'arg1'=>123
        ),
        'description'=>'Test make_args for 1 arg'
);
$_kernel_make_args[]=array(
        'args'=>array(array(123,234),'arg1,arg2'),
        'etalon'=>array(
            'arg1'=>123,
            'arg2'=>234
        ),
        'description'=>'Test make_args for 2 args'
);
$_kernel_make_args[]=array(
        'args'=>array('','arg1,arg2'),
        'etalon'=>false,
        'description'=>'Test make_args for 0 args and 2 needed'
);
$_kernel_make_args[]=array(
        'args'=>array(array(123,321),'arg2'),
        'etalon'=>false,
        'description'=>'Test make_args for 2 args and 1 needed'
);
$_kernel_make_args[]=array(
        'args'=>array(array(123),'arg1,arg2'),
        'etalon'=>array('arg1'=>123),
        'description'=>'Test make_args for 1 args and 2 needed'
);


utest_test_function('_kernel_alias_list', $_kernel_alias_list);
utest_test_function('_kernel_get_domain', $_kernel_get_domain);
utest_test_function('_kernel_get_page', $_kernel_get_page);
utest_test_function('_kernel_get_page_list', $_kernel_get_page_list);
utest_test_function('_kernel_get_page_data', $_kernel_get_page_data);
utest_test_function('_kernel_make_args', $_kernel_make_args);
utest_test_function('_kernel_parse_page', $_kernel_parse_page);
utest_message('Functions _kernel_headers, _kernel_load_domain_config, _kernel_print_page, kernel_start are not tested (no return values)');

?>
