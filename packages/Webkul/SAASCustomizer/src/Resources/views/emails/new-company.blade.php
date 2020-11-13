@component('shop::emails.layouts.master')
    <div class="section-content">
        <div class="table mb-30">
            <table style="overflow-x: auto; border-collapse: collapse;
            border-spacing: 0;width: 100%">
                <thead>
                    <tr style="background-color: #f2f2f2">
                        <th style="text-align: left;padding: 8px">Name</th>

                        <th style="text-align: left;padding: 8px">Email</th>

                        <th style="text-align: left;padding: 8px">Username</th>

                        <th style="text-align: left;padding: 8px">Domain</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td style="text-align: left;padding: 8px">{{ $company->name ?? "---" }}</td>

                        <td style="text-align: left;padding: 8px">{{ $company->email ?? "---" }}</td>

                        <td style="text-align: left;padding: 8px">{{ $company->username ?? "---" }}</td>

                        <td style="text-align: left;padding: 8px">{{ $company->domain ?? "---" }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table mb-20">
            <table style="overflow-x: auto; border-collapse: collapse;
            border-spacing: 0;width: 100%">
                <thead>
                    <tr style="background-color: #f2f2f2">
                        <th style="text-align: left;padding: 8px">First Name</th>

                        <th style="text-align: left;padding: 8px">Last Name</th>

                        <th style="text-align: left;padding: 8px">Email</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td style="text-align: left;padding: 8px">{{ $company->details->first_name ?? "---" }}</td>

                        <td style="text-align: left;padding: 8px">{{ $company->details->last_name ?? "---" }}</td>

                        <td style="text-align: left;padding: 8px">{{ $company->email ?? "---" }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endcomponent