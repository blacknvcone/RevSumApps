<?php

namespace App\Http\Controllers;

use App\CompanyRev;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Maatwebsite\Excel\Facades\Excel;


class CompaniesController extends Controller
{
    /**
     * Display all companies.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        $limit = 10; // get number of records

        // Pagination
        $count = CompanyRev::getTotal();
        $page = $request->input('page') ? $request->input('page') : 1;

        $paginator = new Paginator([], $count, $limit, $page, [
            'path'  => $request->url(),
            'query' => $request->query(),
        ]);

        // Selecting records according to page number
        $offset = ($page-1)*$limit; // get records start from

        $companies = CompanyRev::getRecords($offset, $limit, $request);

        return view('companies.list')
            ->with('companies', $companies)
            ->with('paginator',$paginator)
            ->with('CMGSegmentName', $request['CMGSegmentName'])
            ->with('CMGUnmaskedName', $request['CMGUnmaskedName'])
            ->with('ClientTier', $request['ClientTier']);
    }

    /**
     * Display the specified client.
     *
     * @param  int  $id
     * @throws Exception if client not found
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = CompanyRev::getOne($id);

        if (!$company) {
            throw new \Exception('Company data not found.');
        }

        return view('companies.show', ['company' => $company]);
    }

    /**
     * Update the specified company.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        //return Excel::load(CompanyRev::getStoragePath())->get();

        $updateCompany = CompanyRev::updateOne($request['offset'],$request);

        $company = CompanyRev::getOne($request['offset']);

        if (!$company) {
            throw new \Exception('Company data not found.');
        }




        return view('companies.show', ['company' => $company]);
    }
}
