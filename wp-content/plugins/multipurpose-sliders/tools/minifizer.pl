#!/usr/bin/perl
use strict;
use warnings;
use Carp;
use English qw( -no_match_vars );
use Cwd 'abs_path';

my $theme_name = 'iworks-sliders-admin';

my $root = abs_path($0);
$root =~ s/\/[^\/]+\/[^\/]+$//;

die "no root: $root\n" unless -d $root;

$root .= '/styles';

if ( -f $root.'/'.$theme_name.'.dev.css' ) {
    print "CSS::Minifier:: ".$theme_name.".dev.css -> ".$theme_name.".css\n";
    use CSS::Minifier qw(minify);
    open(INFILE, $root.'/'.$theme_name.'.dev.css') or die;
    open(OUTFILE, '>'.$root.'/'.$theme_name.'.css') or die;
    CSS::Minifier::minify(input => *INFILE, outfile => *OUTFILE );
    close(INFILE);
    close(OUTFILE);
}
else
{
    print "no css: $root/$theme_name.dev.css\n"
}

$root .= '/scripts';

