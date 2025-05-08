document.addEventListener('DOMContentLoaded', function () {
    const kodePosKabupatenBandung = [
        '40374', '40921', '40256', '40385', '40617', '40229', '40394', '40382', 
        '40218', '40267', '40287', '40198', '40225', '40974', '40226', '40618', 
        '40911', '40912', '40191', '40216', '40915', '40375', '40258', '40616', 
        '40196', '40193', '40238', '40395', '40973', '40257', '40379', '40383', 
        '40914', '40217', '40619', '40378', '40227', '40381', '40195', '40384', 
        '40228', '40396', '40913', '40215', '40377', '40972', '40386', '40376'
    ];

    const kodePosKotaBandung = [
        '40137', '40135', '40232', '40117', '40241', '40156', '40134', '40115', 
        '40260', '40144', '40267', '40270', '40279', '40146', '40618', '40127', 
        '40167', '40281', '40179', '40233', '40234', '40235', '40211', '40262', 
        '40192', '40269', '40180', '40266', '40163', '40619', '40265', '40212', 
        '40132', '40158', '40254', '40223', '40255', '40215', '40140', '40138', 
        '40125', '40166', '40251', '40613', '40190', '40611', '40243', '40231', 
        '40159', '40271', '40292', '40148', '40143', '40268', '40287', '40187', 
        '40160', '40214', '40276', '40162', '40114', '40261', '40170', '40294', 
        '40136', '40168', '40189', '40264', '40129', '40239', '40185', '40293', 
        '40238', '40257', '40151', '40116', '40237', '40173', '40227', '40242', 
        '40252', '40291', '40222', '40131', '40172', '40177', '40236', '40182', 
        '40259', '40124', '40296', '40181', '40176', '40284', '40161', '40178', 
        '40133', '40154', '40224', '40221', '40112', '40149', '40225', '40164', 
        '40226', '40277', '40128', '40139', '40165', '40121', '40141', '40275', 
        '40295', '40272', '40263', '40285', '40283', '40175', '40147', '40113', 
        '40280', '40286', '40122', '40256', '40278', '40155', '40615', '40273', 
        '40123', '40617', '40126', '40183', '40157', '40145', '40191', '40174', 
        '40150', '40258', '40616', '40188', '40213', '40142', '40130', '40274', 
        '40184', '40195', '40253', '40111', '40612', '40282', '40169', '40171', 
        '40186', '40152', '40614', '40153'
    ];

    const kotaSelect = document.getElementById('kotaSelect');
    const kodePosSelect = document.getElementById('kodePos');
    const defaultValueKodePos = kodePosSelect.getAttribute('data-kodePos');
    
    console.log(defaultValueKodePos);

    function populateKodePos(options) {
        let Eoption = document.createElement('option');
        Eoption.value = "";
        Eoption.text = "Pilih Kode Pos";
        Eoption.disabled = true;
        Eoption.selected = true;
        kodePosSelect.innerHTML = "";
        kodePosSelect.appendChild(Eoption);
        options.forEach(kode => {
            option = document.createElement('option');
            option.value = kode;
            option.text = kode;
            if (defaultValueKodePos === kode) {
                option.selected = true;
            }
            kodePosSelect.appendChild(option);
        });
    }

    kotaSelect.addEventListener('change', function () {
        if (this.value === 'Kota Bandung') {
            populateKodePos(kodePosKotaBandung);
        } else if (this.value === 'Kabupaten Bandung') {
            populateKodePos(kodePosKabupatenBandung);
        }
    });

    // Populate default selection
    if (kotaSelect.value === 'Kota Bandung') {
        console.log('Populated');
        populateKodePos(kodePosKotaBandung);
    } else if (kotaSelect.value === 'Kabupaten Bandung') {
        console.log('Populated Kabupaten');
        populateKodePos(kodePosKabupatenBandung);
    }
});