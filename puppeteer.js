const puppeteer = require('puppeteer');
const puppeteerFirefox = require('puppeteer-firefox');

const fs = require('fs');

const url_login = 'https://test.tgp.crs/login/login.aspx';
const name = '001550';
const password = 'dixie';


(async function() {
    let files = [];

    const browser = await puppeteer.launch({
        headless: true,
        args: ['--no-sandbox'],
        defaultViewport: {
            width: 1920,
            height: 1080
        },
        slowMo: 100
    });

    const page = await browser.newPage();

    await page.goto(url_login);

    await page.type('#txtName', name);
    await page.type('#txtPassword', password);

    await Promise.all([
        page.click('#SubmitLogin'),
        page.waitForNavigation()
    ]);

    let selector = await page.evaluateHandle(() => document.querySelector('#ctl00_MainContent_ddlMajorDept'))
    await selector.click();

    await page.waitForTimeout(10000);

    page.on('request', request => {
        if(request.url().indexOf('.xlsx') !== -1){
            console.log(request.url())
            files.push(request.url());
        }
    });

    let list_item = await page.evaluateHandle(() => document.querySelector('.rcbList .rcbItem:nth-child(2)'))
    await list_item.click();

    await page.waitForTimeout(30000);

    await Promise.all([
        page.click('#ctl00_MainContent_btnSearch'),
        page.waitForTimeout(60000)
    ]);

    await page.screenshot({path: 'example1.png'});

    await Promise.all([
        page.click('#ctl00_MainContent_btnDownloadResults'),
        page.waitForTimeout(30000)
    ]);


    selector = await page.evaluateHandle(() => document.querySelector('#ctl00_MainContent_ddlMajorDept'))
    await selector.click();

    await page.waitForTimeout(20000);

    list_item = await page.evaluateHandle(() => document.querySelector('.rcbList .rcbItem:nth-child(10)'))
    await list_item.click();

    await page.waitForTimeout(20000);

    await Promise.all([
        page.click('#ctl00_MainContent_btnSearch'),
        page.waitForTimeout(50000)
    ]);

    await Promise.all([
        page.click('#ctl00_MainContent_btnDownloadResults'),
        page.waitForTimeout(25000)
    ]);

    await page.screenshot({path: 'example2.png'});

    console.log(files);

    let data = files.join('\r\n');

    await fs.writeFile('/var/www/html/supplybeaver.ca/parsing/system/csv/links.txt', data, (err) => {
        if (err) throw err;
        console.log('The file has been saved!');
    });


    console.log('ok');

    await browser.close();
})().catch(console.error);