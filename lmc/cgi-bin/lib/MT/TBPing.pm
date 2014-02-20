# Copyright 2001-2003 Six Apart. This code cannot be redistributed without
# permission from www.movabletype.org.
#
# $Id: TBPing.pm,v 1.4 2003/02/12 00:15:03 btrott Exp $

package MT::TBPing;
use strict;

use MT::Object;
@MT::TBPing::ISA = qw( MT::Object );
__PACKAGE__->install_properties({
    columns => [
        'id', 'blog_id', 'tb_id', 'title', 'excerpt', 'source_url', 'ip',
        'blog_name',
    ],
    indexes => {
        created_on => 1,
        blog_id => 1,
        tb_id => 1,
        ip => 1,
    },
    audit => 1,
    datasource => 'tbping',
    primary_key => 'id',
});

1;
