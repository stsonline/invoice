var dispatch = getUrlVars()["dispatch"];


if(true)
{
	$('document').ready(function(){
		var names = new Names();
		var lorem_ipsum = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n\nSed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?';
		
		var mailer_domains = [
		               'gmail.com',
		               'ymail.com',
		               'googlemail.com',
		               'hotmail.com',
		               'bbc.co.uk',
		               'gsi.gov.uk',
		               'me.com',
		               'privatemail.co.uk',
		               'barclaycard.co.uk',
		               'live.com',
		               'live.co.uk'
		               ];
		
		var towns = [
		               'London',
		               'Manchester',
		               'Birmingham',
		               'Bristol',
		               'Uxbridge',
		               'Oxford',
		               'Cambridge',
		               'Cardiff',
		               'Swansea',
		               'Glasgow',
		               'Edinburgh',
		               'Peterborough'
		               ];
		
		var banks = [
		               'Nationwide',
		               'HSBC',
		               'Abbey',
		               'Santander',
		               'LLoyds TSB',
		               'Deutschbank',
		               'Barclays',
		               'Halifax'
		               
		               ];
		
		var employers = [
		               'tesco',
		               'coop',
		               'sainsburys',
		               'kefco sales ltd',
		               'BBC',
		               'NHS Wales',
		               'Debenhams',
		               'Riva',
		               'My time leisure Ltd',
		               'p.s.s',
		               'Rolls Royce Shared Services Ltd',
		               'racing ahead ltd'
		               ];
	
		var jobs = [
		               'sales manager',
		               'retail assistant',
		               'driver',
		               'marketing manager',
		               'publishing assistant',
		               'factory operator',
		               'chef',
		               'sous chef',
		               'fundraiser',
		               'cashier',
		               'Operator',
		               'Senior support worker'
		               ];
		
		var streetnames = [
		               'irving',
		               'donald',
		               'stacey',
		               'vine',
		               'london',
		               'cambridge',
		               'clifton',
		               'bristol',
		               'gloucester',
		               'st claires',
		               'Priory',
		               'old town'
		               ];
		
		var d = new Date();
		var time = d.getTime();
		var year = new Date().getFullYear();
		var following_year = new Date().getFullYear() + 1;

		
		var next_month = 1 + parseInt(new Date().getMonth());
		var following_month = next_month % 11;
		
		var num = randomFromInterval(0,names.data.length);
		var first = names.data[num].RandomName.firstName;
		var last = names.data[num].RandomName.lastName;
		var initial = names.data[num].RandomName.middleInitial;
		
		var email = first+"."+initial+"."+last+"@"+mailer_domains[randomFromInterval(0,mailer_domains.length-1)];
		
		var payday = randomFromInterval(14,30);
		
		var selectors = 
		[
			{"s":"[name='card-type']","v":'Visa'},
			{"s":"[name='card-name']","v":'Test '+time},
			{"s":"[name='card-number']","v":'4543133125540521'},
			{"s":"[name='card-exp-month']","v":'05'},
			{"s":"[name='card-exp-year']","v":'2016'},
			{"s":"[name='cvv-code']","v":'123'},
			{"s":"[name='card-issue']","v":" "},
			{"s":"[name='phone']","v":'07561401968'},
			{"s":"[name='account-email']","v":''},
			{"s":"[name='account-password1']","v":'password'},
			{"s":"[name='account-password2']","v":'password'},
			
			{"s":"[name='signupTextTitle']","v":'Mr'},
			{"s":"[name='email']","v":'will.sinclair2@gmail.com'},
			{"s":"[name='signupTextTitle-feedback']","v":'Mr'},
			{"s":"[name='email-feedback']","v":'will.sinclair2@gmail.com'},
			{"s":"[name='partial-amount']","v":" "},
			
			{"s":"[type='text']","v":time},
			{"s":"textarea","v":time+' '+lorem_ipsum}, 
		];
		
		for(i=0;i< selectors.length;i++)
		{
			
			$(selectors[i].s).each(function()
			{
				if(this.value=='')
				{
					this.value=selectors[i].v;
				}
			});
		}
		
	
	});
}

function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function randomFromInterval(from,to)
{
    return Math.floor(Math.random()*(to-from+1)+from);
}

